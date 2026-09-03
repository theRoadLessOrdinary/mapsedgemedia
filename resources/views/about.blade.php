@extends('layouts.app')

@section('title', 'About')
@section('description', 'About William Morris — UI/UX-focused developer with 20+ years shipping web applications and e-commerce platforms.')

@section('content')
    <section class="max-w-3xl mx-auto px-6 pt-20 pb-12">
        <p class="text-sm font-medium text-indigo-800 mb-4">About</p>
        <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-900">
            William Morris
        </h1>
        <p class="mt-6 text-lg text-slate-700 leading-relaxed">
            UI/UX is where I do my best work &mdash; the part of the job I'd choose even if
            nothing else paid the bills. I've spent over 20 years as a full-stack developer,
            but the throughline across every role has been the same instinct: take something
            confusing or slow and make it clear and fast for the person using it.
        </p>
        <p class="mt-6 text-slate-700 leading-relaxed">
            That's shown up as rebuilding dealership CRMs so staff could actually find what
            they needed, cutting document-generation and front-end load times by up to 75%,
            and most recently founding an e-commerce platform &mdash; TheRoadLessOrdinary /
            EdgeCart &mdash; where I own the interface design, the accessibility standard, and
            the code underneath it, end to end.
        </p>
    </section>

    <section class="max-w-3xl mx-auto px-6 mt-6 pb-16">
        <h2 class="text-xl font-semibold text-slate-900">Experience</h2>

        <ol class="mt-6 space-y-10">
            <li>
                <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-1">
                    <h3 class="font-medium text-slate-900">Founder &amp; Full-Stack Developer</h3>
                    <span class="text-sm text-slate-700">2026 &ndash; Present</span>
                </div>
                <p class="text-sm text-slate-700">TheRoadLessOrdinary / EdgeCart &middot; Independence, MO</p>
                <ul class="mt-3 space-y-2 text-sm text-slate-700 leading-relaxed list-disc list-inside">
                    <li>Built a custom PHP e-commerce platform from the ground up, including plugin licensing and a multi-state sales tax engine.</li>
                    <li>Found and fixed a critical remote code execution vulnerability, then led a full security review across the platform.</li>
                    <li>Developing EdgeCart, a commercial version with a plugin marketplace and licensing/signing system.</li>
                    <li>Contributed accessibility and usability improvements (sortable/resizable columns, dark theme, search, inline editing) to KDE's kfind and the AutoKey automation tool.</li>
                </ul>
            </li>

            <li>
                <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-1">
                    <h3 class="font-medium text-slate-900">Consulting Software Developer</h3>
                    <span class="text-sm text-slate-700">Oct 2025 &ndash; Jun 2026</span>
                </div>
                <p class="text-sm text-slate-700 mt-1">RUCO Products Inc. &middot; Blue Springs, MO</p>
                <ul class="mt-3 space-y-2 text-sm text-slate-700 leading-relaxed list-disc list-inside">
                    <li>Modernized legacy Classic ASP applications onto a SQL Server backend.</li>
                    <li>Improved front-end interface speed by up to 75%.</li>
                    <li>Used Claude AI to parse and refactor complex logic, speeding up development.</li>
                </ul>
            </li>

            <li>
                <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-1">
                    <h3 class="font-medium text-slate-900">Web Applications Developer</h3>
                    <span class="text-sm text-slate-700">Apr 2023 &ndash; Jan 2026</span>
                </div>
                <p class="text-sm text-slate-700 mt-1">Vantage Finance, LLC &middot; Omaha, NE</p>
                <ul class="mt-3 space-y-2 text-sm text-slate-700 leading-relaxed list-disc list-inside">
                    <li>Designed and built a custom CRM for auto finance and sales follow-up.</li>
                    <li>Automated 200+ monthly multi-page documents (Buyer's Guides, Insurance Proof, Contracts).</li>
                    <li>Integrated 700Credit, Dealertrack, JATO, DocuSign, and Arkona, cutting manual data entry by up to 80%.</li>
                </ul>
            </li>

            <li>
                <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-1">
                    <h3 class="font-medium text-slate-900">Partner / Web Applications Developer</h3>
                    <span class="text-sm text-slate-700">Feb 2002 &ndash; Apr 2023</span>
                </div>
                <p class="text-sm text-slate-700 mt-1">Seritas LLC &middot; Liberty, MO</p>
                <ul class="mt-3 space-y-2 text-sm text-slate-700 leading-relaxed list-disc list-inside">
                    <li>Built and managed bespoke dealership websites on a custom CMS.</li>
                    <li>Pioneered an IP-camera-driven image management system, cutting inventory documentation time by up to 70%.</li>
                </ul>
            </li>
        </ol>
    </section>

    <section class="max-w-3xl mx-auto px-6 mt-6 pb-24">
        <h2 class="text-xl font-semibold text-slate-900">Skills</h2>
        <ul class="mt-6 flex flex-wrap gap-2 text-sm">
            @foreach ([
                'UX design', 'PHP', 'MySQL', 'SQL Server', 'JavaScript', 'Classic ASP',
                'E-commerce platform development', 'Security auditing and remediation',
                'Plugin architecture and licensing', 'Front-end performance optimization',
                'Third-party API integration', 'AI feature integration', 'Claude AI',
                'Custom CRM development', 'Document automation',
            ] as $skill)
                <li class="px-3 py-1.5 rounded-full border border-slate-200 text-slate-700">{{ $skill }}</li>
            @endforeach
        </ul>
    </section>
@endsection
