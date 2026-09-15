@extends('layouts.app')

@section('title', $caseStudy['title'])
@section('description', $caseStudy['summary'])

@section('content')
    <article class="max-w-3xl mx-auto px-6 pt-20 pb-24">

        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-medium uppercase tracking-wide border border-amber-300 bg-amber-50 text-amber-800">
            Draft, open questions marked inline
        </span>

        <h1 class="mt-6 text-3xl sm:text-4xl font-semibold tracking-tight text-slate-900">
            {{ $caseStudy['title'] }}
        </h1>
        <p class="mt-6 text-lg text-slate-700 leading-relaxed max-w-2xl">
            {{ $caseStudy['summary'] }}
        </p>

        <dl class="mt-10 grid grid-cols-2 sm:grid-cols-4 gap-6 text-sm border-y border-slate-200 py-6">
            <div>
                <dt class="text-slate-500 uppercase tracking-wide text-xs">Role</dt>
                <dd class="mt-1 text-slate-900 font-medium">Front-end / systems developer</dd>
            </div>
            <div>
                <dt class="text-slate-500 uppercase tracking-wide text-xs">Timeline</dt>
                <dd class="mt-1 text-slate-900 font-medium">?</dd>
            </div>
            <div>
                <dt class="text-slate-500 uppercase tracking-wide text-xs">Team</dt>
                <dd class="mt-1 text-slate-900 font-medium">Solo</dd>
            </div>
            <div>
                <dt class="text-slate-500 uppercase tracking-wide text-xs">Tools</dt>
                <dd class="mt-1 text-slate-900 font-medium">Classic ASP, JavaScript</dd>
            </div>
        </dl>

        <div class="mt-4 p-4 rounded-lg border border-amber-300 bg-amber-50">
            <p class="text-sm font-medium text-amber-800">Question for you</p>
            <p class="mt-1 text-sm text-amber-800 leading-relaxed">
                Is this the same engagement/timeframe as the document library piece, or a separate
                stint? Filled in "Solo" and the tools but left Timeline blank above.
            </p>
        </div>

        {{-- The problem --}}
        <section class="mt-14">
            <h2 class="text-xl font-semibold text-slate-900">The problem</h2>
            <p class="mt-6 text-slate-700 leading-relaxed">
                RUCO's app handled printing through an ActiveX control, which meant the app could
                only run in Internet Explorer, or in Edge's "IE mode," a compatibility layer
                Microsoft has already begun sunsetting. That's not a cosmetic problem: once IE mode
                is gone, printing stops working for everyone, in every department that used it, with
                no fallback.
            </p>
            <p class="mt-6 text-slate-700 leading-relaxed">
                The obvious fix, replace ActiveX with a real HTML/CSS print stylesheet and the
                browser's native print dialog, ran into a complication. Printing wasn't just
                printing. Every print action ran through a shared page that also updated inventory
                and sent notifications, before handing off to a second app loaded in an iframe that
                actually built the document. The ActiveX control only took over at the very end of
                that chain, once the iframe had finished loading. Printing was load-bearing for
                business logic that had nothing to do with printing.
            </p>
        </section>

        <div class="mt-6 p-4 rounded-lg border border-amber-300 bg-amber-50">
            <p class="text-sm font-medium text-amber-800">Question for you</p>
            <p class="mt-1 text-sm text-amber-800 leading-relaxed">
                Worth naming a real timeframe for the IE mode sunset here to make the urgency
                concrete, if you have one you're comfortable citing? Left it generic for now.
            </p>
        </div>

        {{-- Process --}}
        <section class="mt-14">
            <h2 class="text-xl font-semibold text-slate-900">Process</h2>
            <p class="mt-6 text-slate-700 leading-relaxed">
                Tracing the actual flow: clicking a print button or link navigated to a "prepare
                print" page. That page ran its inventory and notification logic, then loaded a
                second app inside an iframe. The iframe's <code>src</code> was the real document
                URL, and ActiveX took it from there.
            </p>
            <p class="mt-6 text-slate-700 leading-relaxed">
                Rewriting this properly, dropping ActiveX and printing the document directly, meant
                finding and updating every print button and link across the app first. There were
                dozens, built up over years, with no consistent markup to search for reliably in one
                pass. Fixing them one at a time also meant the app would be half-working for however
                long the rollout took: some pages fixed, most not.
            </p>
            <p class="mt-6 text-slate-700 leading-relaxed">
                The fix that actually shipped left every print button exactly where it was, and
                changed nothing about the inventory/notification logic. It intercepted the click
                itself:
            </p>
            <ol class="mt-6 space-y-3 text-slate-700 leading-relaxed list-decimal list-inside">
                <li>A script, run on every page load, scans for anything acting as a print button or link.</li>
                <li>On click, it hijacks the event before the browser navigates anywhere.</li>
                <li>Instead of navigating, it fetches the same "prepare print" URL the button always pointed at, so the inventory and notification logic on the server still runs exactly as before, unchanged.</li>
                <li>It reads the returned HTML, pulls out the iframe's <code>src</code>, the actual document URL.</li>
                <li>It opens that URL directly in a new window, letting the browser print it natively. ActiveX never runs.</li>
            </ol>
            <p class="mt-6 text-slate-700 leading-relaxed">
                Deployment skipped the dozens-of-pages problem entirely: the script was injected at
                the top of every Classic ASP page by the web server itself, so it went live
                everywhere in the app the moment it shipped, with nothing to roll out page by page.
            </p>
        </section>

        <div class="mt-6 p-4 rounded-lg border border-amber-300 bg-amber-50">
            <p class="text-sm font-medium text-amber-800">Question for you</p>
            <p class="mt-1 text-sm text-amber-800 leading-relaxed">
                Since the new window opens after an async <code>fetch()</code> resolves, rather than
                synchronously inside the click handler, did you run into popup blockers? A common
                workaround is opening a blank window immediately on click, then setting its
                <code>location</code> once the fetch resolves. Is that what happened here, or
                did it just work without one?
            </p>
        </div>

        {{-- Key decisions --}}
        <section class="mt-14">
            <h2 class="text-xl font-semibold text-slate-900">Key decisions</h2>
            <ul class="mt-6 space-y-6">
                <li>
                    <p class="font-medium text-slate-900">Preserve the request, replace only the delivery.</p>
                    <p class="mt-2 text-slate-700 leading-relaxed">Fetching the same prepare-print URL kept the inventory and notification logic completely intact. Only the last step, handing the document to ActiveX, got swapped out.</p>
                </li>
                <li>
                    <p class="font-medium text-slate-900">Intercept at the click, not at every page.</p>
                    <p class="mt-2 text-slate-700 leading-relaxed">Hijacking the click event client-side meant never having to find and edit each of the dozens of print entry points individually.</p>
                </li>
                <li>
                    <p class="font-medium text-slate-900">Deploy through the server, not the app.</p>
                    <p class="mt-2 text-slate-700 leading-relaxed">Injecting the script into every page's header at the server level meant the fix applied everywhere at once, with no partial rollout to track or leave unfinished.</p>
                </li>
            </ul>
        </section>

        {{-- Outcome --}}
        <section class="mt-14 pb-4">
            <h2 class="text-xl font-semibold text-slate-900">Outcome</h2>
            <p class="mt-6 text-slate-700 leading-relaxed">
                The app's printing stopped depending on Edge's IE compatibility mode, sitewide, in a
                single deploy, with zero changes to any individual print button or page. The
                underlying business logic, inventory updates and notifications, kept working
                exactly as it had before, since the fix reused the same request rather than
                replacing it.
            </p>
        </section>

        <div class="mt-6 p-4 rounded-lg border border-amber-300 bg-amber-50">
            <p class="text-sm font-medium text-amber-800">Question for you</p>
            <p class="mt-1 text-sm text-amber-800 leading-relaxed">
                Two things worth having if you've got them: a rough count of how many print
                buttons/pages this replaced (to make "dozens" concrete), and whether the ActiveX
                control itself was fully removed afterward or just stopped being invoked.
            </p>
        </div>

        <div class="pt-8 border-t border-slate-200">
            <a href="{{ route('work') }}" class="text-sm font-medium text-indigo-800 hover:underline">&larr; Back to case studies</a>
        </div>

    </article>
@endsection
