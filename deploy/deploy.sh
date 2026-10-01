#!/usr/bin/env bash
# Deploys this app to HelioHost using the split-directory layout described
# in deploy/README.md.
#
# Usage: deploy/deploy.sh
#
# What it does:
#   1. Builds front-end assets locally (npm run build).
#   2. Builds a clean, production-only copy of the app (composer install
#      --no-dev) in a scratch directory, never touches your local dev
#      vendor/.
#   3. Syncs that app copy to heliohost:mapsedgemedia-app/ (NOT web-
#      accessible) via `rclone sync` directly against the FTP backend.
#   4. Syncs public/'s built contents to heliohost:mapsedgemedia.com/ (the
#      actual docroot) the same way, swapping in
#      deploy/index.production.php as index.php.
#   5. Verifies both syncs actually landed by checking file counts/sizes
#      against the backend afterward, NOT by reading anything back
#      through the ~/helio mount, which has been observed to silently
#      accept writes that never reach the real server (see deploy/README.md
#      for the full story). Every step below talks to the `heliohost:`
#      rclone remote directly.
#
# What it deliberately does NOT do:
#   - Touch .env in either location. On first deploy, create it by hand
#     and push it with `rclone copyto` (see deploy/README.md), never
#     overwrite it automatically, since it holds APP_KEY/DB config that
#     must persist across deploys.
#   - Touch database/database.sqlite in the app dir, for the same reason.
#   - Run migrations. Run `php ~/helio/mapsedgemedia-app/artisan migrate`
#     yourself after reviewing what changed, the same as you would for any
#     other production deploy.

set -euo pipefail

PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
REMOTE="heliohost"
APP_REMOTE="$REMOTE:mapsedgemedia-app"
WEB_REMOTE="$REMOTE:mapsedgemedia.com"
SCRATCH="$(mktemp -d)"
trap 'rm -rf "$SCRATCH"' EXIT

echo "==> Building front-end assets"
cd "$PROJECT_ROOT"
npm ci
npm run build

echo "==> Building a clean production copy of the app in $SCRATCH"
rsync -a \
    --exclude '.git' \
    --exclude 'node_modules' \
    --exclude 'vendor' \
    --exclude 'public' \
    --exclude 'tests' \
    --exclude '.env' \
    --exclude '.env.example' \
    --exclude 'database/database.sqlite' \
    --exclude 'storage/logs' \
    --exclude 'storage/framework/cache' \
    --exclude 'storage/framework/sessions' \
    --exclude 'storage/framework/views' \
    "$PROJECT_ROOT/" "$SCRATCH/"

(cd "$SCRATCH" && composer install --no-dev --optimize-autoloader --no-interaction)

echo "==> Syncing app code to $APP_REMOTE (not web-accessible)"
rclone sync "$SCRATCH/" "$APP_REMOTE" \
    --exclude '.env' \
    --exclude 'database/database.sqlite' \
    --exclude 'storage/logs/**' \
    --transfers 8 --checkers 8 --stats 15s --stats-one-line

# Placeholder files so empty required directories survive the FTP backend
# (rclone/FTP don't reliably keep empty directories otherwise).
for d in storage/framework/cache storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs; do
    printf '' | rclone rcat "$APP_REMOTE/$d/.gitkeep"
done

echo "==> Syncing built public assets to $WEB_REMOTE (the actual docroot)"
rclone sync "$PROJECT_ROOT/public/" "$WEB_REMOTE" \
    --exclude 'index.php' \
    --transfers 8 --checkers 8 --stats 15s --stats-one-line
rclone copyto "$PROJECT_ROOT/deploy/index.production.php" "$WEB_REMOTE/index.php"

echo "==> Verifying against the real backend"
# HelioHost's FTP regularly drops connections mid-listing, so each check is
# retried before it counts as a failure. A timeout here does not mean the
# upload failed (seen 2026-10-01: sync succeeded, size check timed out).
retry() {
    local attempt
    for attempt in 1 2 3; do
        if "$@"; then return 0; fi
        [ "$attempt" -lt 3 ] || break
        echo "    (attempt $attempt failed, retrying in $((attempt * 10))s)" >&2
        sleep $((attempt * 10))
    done
    return 1
}

# rclone's own FTP timeouts default to minutes, long enough for one stalled
# listing to hang the whole deploy, so every call below is capped with
# `timeout`. Sizes are informational only: one try each, and a failure here
# doesn't fail the deploy.
APP_COUNT_SIZE="$(timeout 120 rclone size "$APP_REMOTE" --contimeout 30s --timeout 60s --json || echo 'unavailable (listing failed)')"
WEB_COUNT_SIZE="$(timeout 120 rclone size "$WEB_REMOTE" --contimeout 30s --timeout 60s --json || echo 'unavailable (listing failed)')"
echo "    $APP_REMOTE: $APP_COUNT_SIZE"
echo "    $WEB_REMOTE: $WEB_COUNT_SIZE"

# The two files the site can't run without. Checked by exact path rather
# than by listing whole directories, which is where the timeouts happen.
if ! retry timeout 90 rclone lsl "$WEB_REMOTE/index.php" --contimeout 30s --timeout 60s >/dev/null; then
    echo "ERROR: index.php did not land in $WEB_REMOTE, deploy did not actually complete." >&2
    exit 1
fi
if ! retry timeout 90 rclone lsl "$APP_REMOTE/vendor/autoload.php" --contimeout 30s --timeout 60s >/dev/null; then
    echo "ERROR: vendor/autoload.php did not land in $APP_REMOTE, deploy did not actually complete." >&2
    exit 1
fi

echo "==> Done and verified against the real server."
echo "    If this is the first deploy: create .env locally, push it with"
echo "    'rclone copyto .env $APP_REMOTE/.env', create the SQLite file with"
echo "    'rclone rcat $APP_REMOTE/database/database.sqlite </dev/null', then run:"
echo "      php ~/helio/mapsedgemedia-app/artisan migrate --force"
echo "    (skip storage:link, this app doesn't use the public storage"
echo "    disk, and symlink() fails over the WebDAV mount anyway)"
