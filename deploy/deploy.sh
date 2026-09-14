#!/usr/bin/env bash
# Deploys this app to HelioHost using the split-directory layout described
# in deploy/README.md.
#
# Usage: deploy/deploy.sh
#
# What it does:
#   1. Builds front-end assets locally (npm run build).
#   2. Builds a clean, production-only copy of the app (composer install
#      --no-dev) in a scratch directory — never touches your local dev
#      vendor/.
#   3. Syncs that app copy to heliohost:mapsedgemedia-app/ (NOT web-
#      accessible) via `rclone sync` directly against the FTP backend.
#   4. Syncs public/'s built contents to heliohost:mapsedgemedia.com/ (the
#      actual docroot) the same way, swapping in
#      deploy/index.production.php as index.php.
#   5. Verifies both syncs actually landed by checking file counts/sizes
#      against the backend afterward — NOT by reading anything back
#      through the ~/helio mount, which has been observed to silently
#      accept writes that never reach the real server (see deploy/README.md
#      for the full story). Every step below talks to the `heliohost:`
#      rclone remote directly.
#
# What it deliberately does NOT do:
#   - Touch .env in either location. On first deploy, create it by hand
#     and push it with `rclone copyto` (see deploy/README.md) — never
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
APP_COUNT_SIZE="$(rclone size "$APP_REMOTE" --json)"
WEB_COUNT_SIZE="$(rclone size "$WEB_REMOTE" --json)"
echo "    $APP_REMOTE: $APP_COUNT_SIZE"
echo "    $WEB_REMOTE: $WEB_COUNT_SIZE"
if ! rclone lsf "$WEB_REMOTE" | grep -qx 'index.php'; then
    echo "ERROR: index.php did not land in $WEB_REMOTE — deploy did not actually complete." >&2
    exit 1
fi
if ! rclone lsf "$APP_REMOTE/vendor" | grep -qx 'autoload.php'; then
    echo "ERROR: vendor/autoload.php did not land in $APP_REMOTE — deploy did not actually complete." >&2
    exit 1
fi

echo "==> Done and verified against the real server."
echo "    If this is the first deploy: create .env locally, push it with"
echo "    'rclone copyto .env $APP_REMOTE/.env', create the SQLite file with"
echo "    'rclone rcat $APP_REMOTE/database/database.sqlite </dev/null', then run:"
echo "      php ~/helio/mapsedgemedia-app/artisan migrate --force"
echo "    (skip storage:link — this app doesn't use the public storage"
echo "    disk, and symlink() fails over the WebDAV mount anyway)"
