# Deployment (HelioHost)

## Why this exists

The previous deploy (Plesk's Git Repositories panel, deploying the whole
repo straight into the domain's docroot) hit a real, unfixed HelioHost bug:
Apache resolved requests from the project root instead of `public/`, which
made `vendor/*.php` directly downloadable and 404'd every route except `/`.
HelioHost's stated fix for "Laravel issues" is "start over" — they wipe the
account rather than fix the vhost — so the same custom-docroot approach
should be assumed to fail the same way again.

This deploy avoids the problem entirely instead of relying on HelioHost to
serve a subfolder correctly: **the app is split into two directories**, and
only one of them is ever inside a web-served folder.

```
~/mapsedgemedia-app/        <- full app (vendor/, .env, database/, app/, …)
                                NOT inside any domain's docroot — never
                                served, regardless of what Apache's default
                                document root does.

~/mapsedgemedia.com/        <- the actual docroot. Contains ONLY the built
                                contents of public/ plus a production
                                index.php that points at the sibling
                                mapsedgemedia-app/ directory instead of "..".
```

Nothing sensitive is reachable via any URL because nothing sensitive is
ever placed under the served folder — this isn't renaming a file to
something unguessable (which is what was done last time for
`database.sqlite`), it's removing the file from underneath the web server
altogether.

## How it works

- `bootstrap/app.php` checks, structurally, whether a sibling
  `mapsedgemedia.com/` directory exists next to the app root. If it does,
  it calls Laravel's own `Application::usePublicPath()` so `public_path()`
  and Vite's asset/manifest lookups resolve to that real docroot instead of
  the app's own (unused, in production) `public/` folder. Locally, no such
  sibling exists, so this safely no-ops and the app behaves normally.

  This is deliberately **not** read from `.env` (an earlier version of this
  tried `env('APP_PUBLIC_PATH')`): in Laravel 11+, `.env` isn't loaded yet
  at the point `bootstrap/app.php` first runs — dotenv loading happens
  later, as part of the HTTP kernel's own bootstrap sequence — so `env()`
  here always silently returns `null`, no error, just no effect. Detecting
  the split by directory structure instead sidesteps that ordering problem
  entirely.
- `deploy/index.production.php` is a copy of `public/index.php` adjusted to
  require `vendor/autoload.php` and `bootstrap/app.php` from
  `../mapsedgemedia-app/` instead of `../`. This is the only file that
  differs between local dev and production — nothing else in the app needs
  to know about the split, since every other path in Laravel resolves
  relative to `bootstrap/app.php`'s own location.

## The `~/helio` mount is not reliable for verification — or even for writes

`~/helio` is an rclone FTP mount (`--vfs-cache-mode writes`). Writes are
cached locally and uploaded to the real FTP server asynchronously. This
mount **silently accepted writes that never actually reached the server**:
a full `deploy.sh` run reported success, and `ls`/`du` through the mount
showed correct file contents and sizes — but `mapsedgemedia.com/`'s actual
files, most of `mapsedgemedia-app/vendor/`, `.env`, and some
`storage/framework/` subdirectories were all missing server-side, silently,
with no error surfaced anywhere. `ls`/`du`/`cat` through this mount only
prove what's in the *local* write cache, not what's on HelioHost.

`deploy/deploy.sh` has since been rewritten to talk to the `heliohost:`
rclone remote directly (`rclone sync`/`copyto`) instead of writing through
`~/helio`, and it verifies file counts/sizes against the backend
afterward. Anything done by hand outside of `deploy.sh` (`.env`, the
database file, one-off scripts) should follow the same rule — use `rclone`
directly against the backend, never `~/helio`:

```sh
rclone size heliohost:mapsedgemedia.com          # real byte counts
rclone lsf heliohost:mapsedgemedia.com --recursive
rclone copy  ./local/dir/ heliohost:remote/dir    # or `sync` — synchronous, reports real errors
rclone copyto ./local/file heliohost:remote/path  # single file
rclone cat heliohost:remote/path/to/file           # read a file straight from the server
```

## First-time setup

1. Run `deploy/deploy.sh` to build assets, build a clean
   `composer install --no-dev` copy of the app, and sync both directories
   directly against the `heliohost:` backend. It verifies the sync landed
   (checks `index.php` and `vendor/autoload.php` are actually present
   server-side) and fails loudly if not.
2. Create `.env` and push it with `rclone copyto` (not through the mount).
   Copy `.env.example` as a starting point; at minimum, production needs:
   ```
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://mapsedgemedia.com
   DB_CONNECTION=sqlite
   ```
   (Leave `DB_DATABASE` unset — it defaults to `database_path('database.sqlite')`,
   which resolves correctly on the server regardless of its actual absolute
   account path.) Generate `APP_KEY` locally with
   `php artisan key:generate --show` and paste the result in.
3. Create the SQLite file and required cache directories directly against
   the backend, e.g. `rclone rcat heliohost:mapsedgemedia-app/database/database.sqlite </dev/null`
   and `rclone rcat heliohost:mapsedgemedia-app/storage/framework/<dir>/.gitkeep </dev/null`
   for `cache`, `cache/data`, `sessions`, `views` (FTP/rclone don't reliably
   keep empty directories otherwise).
4. Run migrations — see below. Skip `storage:link`: this app doesn't use
   the public storage disk (no user uploads — case studies live in code),
   and `symlink()` fails over the WebDAV mount anyway. If the app ever
   grows a feature that needs it, that'll need the one-off-script approach
   below instead of running it through the mount.
5. Verify the live site actually renders (not just returns 200) — check
   for a real page body, not a directory listing or blank response, and
   check `storage/logs/laravel.log` (`rclone cat
   heliohost:mapsedgemedia-app/storage/logs/laravel.log`) for anything
   `APP_DEBUG=false` would otherwise hide.

## Running `artisan` commands against this deploy

There's no SSH access to HelioHost (confirmed previously), so `artisan`
can't be run server-side directly. Two options:

- **Via the mount**: `php ~/helio/mapsedgemedia-app/artisan migrate --force`
  run from your local machine. This works because PHP CLI runs locally
  against files that happen to live on the WebDAV mount — it does execute,
  but every file read/write crosses the mount, so it's slow, and SQLite
  over WebDAV has no real file-locking guarantees. Fine for occasional
  migrations; don't rely on it for anything performance-sensitive or
  concurrent. Confirmed working in practice for `migrate`.
- **Via a one-off script** (the established pattern for this exact
  situation, used previously for GA4/EdgeCart DB work when direct access
  was blocked, and used to diagnose the `public_path()` bug above): write a
  small PHP script, push it with `rclone copyto` straight into the real
  docroot, hit it once over HTTPS, read the output, then delete it
  (`rclone deletefile heliohost:mapsedgemedia.com/<script>.php`)
  immediately after. Preferred when you want something to actually run on
  HelioHost's own PHP rather than through the mount, or when you need to
  inspect runtime state (resolved paths, env values, etc.) rather than run
  an artisan command.

## Redeploying after the first time

Just re-run `deploy/deploy.sh`. It syncs code and rebuilt assets with
`--delete`, but explicitly excludes `.env` and `database.sqlite` in both
the scratch build and the final sync, so neither is ever overwritten by a
routine deploy.

## Backups

A daily off-server backup is set up (as of 2026-09-14), same pattern as
the existing TRLO rclone/Scramble mirror timer:

- `~/bin/mapsedgemedia-db-backup.sh` — copies `database.sqlite` and `.env`
  straight from the `heliohost:` FTP backend (not through `~/helio`) into
  a dated snapshot folder under
  `/media/william/8TB-DRIVE/backups/mapsedgemedia-db/YYYY-MM-DD/`. Dated
  snapshots, not a single overwritten copy, so one bad day doesn't erase
  the last good backup. No automatic pruning (the DB is tiny) — prune old
  dates by hand if this ever matters.
- `~/.config/systemd/user/mapsedgemedia-db-backup.{service,timer}` — runs
  it daily (`systemctl --user list-timers` to check next run;
  `systemctl --user start mapsedgemedia-db-backup.service` to run it now).
- Log: `~/.local/share/mapsedgemedia-db-backup.log`.

This is what was missing when HelioHost's account reset lost the database
with no way to recover it — check that this timer is still enabled if
mapsedgemedia work resumes after a long gap.
