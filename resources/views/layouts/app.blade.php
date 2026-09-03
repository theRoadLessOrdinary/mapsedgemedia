<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Maps Edge Media') — William Morris</title>
    <meta name="description" content="@yield('description', 'UI/UX & full-stack developer portfolio — William Morris, Maps Edge Media.')">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-screen bg-white text-slate-900 antialiased flex flex-col font-sans">

    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 focus:z-50 focus:bg-slate-900 focus:text-white focus:px-4 focus:py-2 focus:rounded">
        Skip to content
    </a>

    @include('partials.header')

    <main id="main" class="flex-1">
        @yield('content')
    </main>

    @include('partials.footer')

</body>
</html>
