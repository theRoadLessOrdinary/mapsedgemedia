@extends('layouts.app')

@section('title', $caseStudy['title'])
@section('description', $caseStudy['summary'])

@section('content')
    <article class="max-w-3xl mx-auto px-6 pt-20 pb-24">

        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-medium uppercase tracking-wide border border-amber-300 bg-amber-50 text-amber-800">
            Concept case study &mdash; sample project, not a client engagement
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
                <dd class="mt-1 text-slate-900 font-medium">Product &amp; UX design, front-end build</dd>
            </div>
            <div>
                <dt class="text-slate-500 uppercase tracking-wide text-xs">Timeline</dt>
                <dd class="mt-1 text-slate-900 font-medium">6 weeks</dd>
            </div>
            <div>
                <dt class="text-slate-500 uppercase tracking-wide text-xs">Team</dt>
                <dd class="mt-1 text-slate-900 font-medium">Solo, with two dispatcher stakeholders</dd>
            </div>
            <div>
                <dt class="text-slate-500 uppercase tracking-wide text-xs">Tools</dt>
                <dd class="mt-1 text-slate-900 font-medium">PHP, MySQL, vanilla JS</dd>
            </div>
        </dl>

        {{-- The problem --}}
        <section class="mt-14">
            <h2 class="text-xl font-semibold text-slate-900">The problem</h2>
            <p class="mt-6 text-slate-700 leading-relaxed">
                Harborline runs same-day local freight with a small fleet and two dispatchers.
                Their scheduling board was a shared spreadsheet: one tab per day, driver names
                typed into cells by hand, colors used inconsistently to mean "confirmed,"
                "tentative," or just whatever color was closest when someone was in a hurry.
            </p>
            <p class="mt-6 text-slate-700 leading-relaxed">
                When a driver called in sick or a load fell through, whoever noticed first
                would scroll through the sheet looking for an open slot, ask the other
                dispatcher out loud if they'd already moved something, and hope nobody was
                editing the same cell at the same time. Double-bookings and missed pickups
                traced back to this almost every week.
            </p>
        </section>

        {{-- Process --}}
        <section class="mt-14">
            <h2 class="text-xl font-semibold text-slate-900">Process</h2>
            <p class="mt-6 text-slate-700 leading-relaxed">
                I spent the first week just watching. Both dispatchers worked a normal shift
                with me next to them, narrating what they were doing and why. A few things
                became clear fast:
            </p>
            <ul class="mt-6 space-y-3 text-slate-700 leading-relaxed list-disc list-inside">
                <li>They weren't looking for a calendar &mdash; they were looking for <em>the next open truck</em>, constantly, all day.</li>
                <li>"Confirmed" vs. "tentative" mattered more than the actual time of day in almost every decision they made.</li>
                <li>Neither of them trusted the spreadsheet enough to stop double-checking it out loud with each other &mdash; the tool had lost their trust before I ever showed up.</li>
            </ul>
            <p class="mt-6 text-slate-700 leading-relaxed">
                That reframed the project. This wasn't "digitize a spreadsheet" &mdash; it was
                "build something dispatchers trust enough to stop cross-checking by voice."
                I sketched three low-fidelity layouts on paper with them in the room, watching
                which one they pointed at first before I'd even finished explaining it.
            </p>
        </section>

        {{-- Before / after --}}
        <section class="mt-14">
            <h2 class="text-xl font-semibold text-slate-900">Before &amp; after</h2>
            <p class="mt-6 text-slate-700 leading-relaxed">
                Simplified mockups of the old spreadsheet-as-schedule versus the redesigned
                board, focused on the one moment that mattered most: finding an open truck.
            </p>

            <div class="mt-8 grid sm:grid-cols-2 gap-6">
                {{-- Before --}}
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500 mb-3">Before &mdash; shared spreadsheet</p>
                    <div class="rounded-lg border border-slate-200 overflow-hidden text-xs">
                        <div class="grid grid-cols-4 bg-slate-100 text-slate-500 font-medium">
                            <div class="px-2 py-1.5 border-r border-b border-slate-200">Truck</div>
                            <div class="px-2 py-1.5 border-r border-b border-slate-200">8a</div>
                            <div class="px-2 py-1.5 border-r border-b border-slate-200">11a</div>
                            <div class="px-2 py-1.5 border-b border-slate-200">2p</div>
                        </div>
                        @foreach (['T-04', 'T-11', 'T-07'] as $truck)
                            <div class="grid grid-cols-4">
                                <div class="px-2 py-1.5 border-r border-b border-slate-200 text-slate-700">{{ $truck }}</div>
                                <div class="px-2 py-1.5 border-r border-b border-slate-200 bg-yellow-100 text-slate-700">Maybe?</div>
                                <div class="px-2 py-1.5 border-r border-b border-slate-200 bg-red-100 text-slate-700">J.R.</div>
                                <div class="px-2 py-1.5 border-b border-slate-200 text-slate-400">&mdash;</div>
                            </div>
                        @endforeach
                    </div>
                    <p class="mt-3 text-xs text-slate-500 leading-relaxed">
                        Color used inconsistently; "open" looks identical to "not filled in yet."
                    </p>
                </div>

                {{-- After --}}
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500 mb-3">After &mdash; dispatch board</p>
                    <div class="rounded-lg border border-slate-200 overflow-hidden text-xs">
                        <div class="grid grid-cols-4 bg-slate-900 text-white font-medium">
                            <div class="px-2 py-1.5 border-r border-slate-700">Truck</div>
                            <div class="px-2 py-1.5 border-r border-slate-700">8a</div>
                            <div class="px-2 py-1.5 border-r border-slate-700">11a</div>
                            <div class="px-2 py-1.5">2p</div>
                        </div>
                        @foreach (['T-04', 'T-11', 'T-07'] as $i => $truck)
                            <div class="grid grid-cols-4">
                                <div class="px-2 py-1.5 border-r border-b border-slate-200 text-slate-700">{{ $truck }}</div>
                                <div class="px-2 py-1.5 border-r border-b border-slate-200 bg-emerald-100 text-emerald-800 font-medium">Open</div>
                                <div class="px-2 py-1.5 border-r border-b border-slate-200 bg-slate-100 text-slate-700">J. Rios &middot; confirmed</div>
                                <div class="px-2 py-1.5 border-b border-slate-200 bg-emerald-100 text-emerald-800 font-medium">Open</div>
                            </div>
                        @endforeach
                    </div>
                    <p class="mt-3 text-xs text-slate-500 leading-relaxed">
                        "Open" is a distinct color from "tentative" is a distinct color from "confirmed," always.
                    </p>
                </div>
            </div>
        </section>

        {{-- Key decisions --}}
        <section class="mt-14">
            <h2 class="text-xl font-semibold text-slate-900">Key decisions</h2>
            <ul class="mt-6 space-y-6">
                <li>
                    <p class="font-medium text-slate-900">Status got its own color channel, permanently.</p>
                    <p class="mt-2 text-slate-700 leading-relaxed">Open, tentative, and confirmed each got one fixed color used nowhere else on the board. No exceptions, no "just this once" overrides &mdash; that consistency is what the spreadsheet never had.</p>
                </li>
                <li>
                    <p class="font-medium text-slate-900">The board updates for both dispatchers at once.</p>
                    <p class="mt-2 text-slate-700 leading-relaxed">A change either dispatcher makes appears for the other within a couple seconds, so the out-loud double-checking has nothing left to catch that the screen didn't already show.</p>
                </li>
                <li>
                    <p class="font-medium text-slate-900">No blank cells &mdash; every slot is either "Open" or has a name on it.</p>
                    <p class="mt-2 text-slate-700 leading-relaxed">An empty cell used to mean three different things depending on who you asked. Every cell now renders as an explicit state, so there's nothing to interpret.</p>
                </li>
            </ul>
        </section>

        {{-- Outcome --}}
        <section class="mt-14 pb-4">
            <h2 class="text-xl font-semibold text-slate-900">Outcome</h2>
            <p class="mt-6 text-slate-700 leading-relaxed">
                As a concept project, there's no live deployment or real usage data to report.
                The measure that mattered in testing sessions with both dispatchers was
                simpler: how long it took each of them to find and confirm an open truck for
                a same-day request, walked through against three realistic scenarios built
                from the patterns I'd watched during the first week. Both dispatchers reached
                a confirmed booking in the new layout without once asking "did you already
                move that?" &mdash; the exact question the old spreadsheet couldn't stop
                triggering.
            </p>
        </section>

        <div class="pt-8 border-t border-slate-200">
            <a href="{{ route('work') }}" class="text-sm font-medium text-indigo-800 hover:underline">&larr; Back to case studies</a>
        </div>

    </article>
@endsection
