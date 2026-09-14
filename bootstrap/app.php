<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();

// On hosts that can't be trusted to serve this app's own public/ folder as
// the docroot (see deploy/README.md), the app itself is deployed outside
// the web-served directory and only the built public/ assets are copied
// into a sibling docroot folder. Point Laravel at that separate,
// actually-served location so public_path()/Vite asset lookups still
// resolve correctly.
//
// This is detected structurally (does the sibling folder exist?) rather
// than read from .env, because .env isn't loaded yet at this point in
// bootstrap — in Laravel 11+, dotenv loading happens later, as part of
// the HTTP kernel's own bootstrap sequence, not when this file first
// runs. env() here would silently always return null. Locally, no
// sibling "mapsedgemedia.com" directory exists next to the app root, so
// this safely no-ops and normal <app>/public behavior is unchanged.
// (dirname(__DIR__, 2): __DIR__ is .../mapsedgemedia-app/bootstrap, so one
// dirname() lands on the app root itself — need a second to reach its
// sibling.)
$realPublicPath = dirname(__DIR__, 2).'/mapsedgemedia.com';
if (is_dir($realPublicPath)) {
    $app->usePublicPath($realPublicPath);
}

return $app;
