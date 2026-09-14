<?php

// Production docroot front controller.
//
// This is NOT the same file as public/index.php. It's a copy adjusted for
// the split-directory layout described in deploy/README.md: this file lives
// in the actual web-served docroot, while the rest of the app (vendor/,
// bootstrap/, app/, .env, database/) lives one level up in a sibling
// directory that is never served, e.g.:
//
//   ~/mapsedgemedia-app/        <- app code, not web-accessible
//   ~/mapsedgemedia.com/        <- docroot; only this file + built public/
//                                  assets live here
//
// This exists because we can't rely on the host serving this Laravel app's
// own public/ folder as the docroot (see deploy/README.md for why) — so
// instead, only the actual public/ folder's *contents* are copied into
// whatever the host serves by default, and the app root is renamed to
// point at the sibling app directory instead of "..".

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

const APP_ROOT = __DIR__.'/../mapsedgemedia-app';

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = APP_ROOT.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require APP_ROOT.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once APP_ROOT.'/bootstrap/app.php';

$app->handleRequest(Request::capture());
