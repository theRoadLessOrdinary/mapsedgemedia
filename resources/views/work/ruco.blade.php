@extends('layouts.app')

@section('title', $caseStudy['title'])
@section('description', $caseStudy['summary'])

@section('content')
    <article class="max-w-3xl mx-auto px-6 pt-20 pb-24">

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
                <dd class="mt-1 text-slate-900 font-medium">1 Week</dd>
            </div>
            <div>
                <dt class="text-slate-500 uppercase tracking-wide text-xs">Team</dt>
                <dd class="mt-1 text-slate-900 font-medium">Solo, with four administrative stakeholders</dd>
            </div>
            <div>
                <dt class="text-slate-500 uppercase tracking-wide text-xs">Tools</dt>
                <dd class="mt-1 text-slate-900 font-medium">ClassicASP, SQL Server, jQuery, vanilla JS</dd>
            </div>
        </dl>

        {{-- The problem --}}
        <section class="mt-14">
            <h2 class="text-xl font-semibold text-slate-900">The problem</h2>
            <p class="mt-6 text-slate-700 leading-relaxed">
                Over the years, the company had accumulated several hundred documents, a fair number of which were in at least weekly use.
                The list was built as just that: a simple list of links pointing to stored PDFs and .doc files. The searching was limited
                and took up otherwise usable space. The list was crowded, including information that was not relevant to the end user.
                If a document was uploaded with a duplicate name, the old file was simply overwritten without confirmation, making information
                loss a genuine risk. There was only one version of each file, no history available.
            </p>
        </section>

        {{-- Process --}}
        <section class="mt-14">
            <h2 class="text-xl font-semibold text-slate-900">Process</h2>

            <h3 class="mt-8 font-medium text-slate-900">Page load time</h3>
            <p class="mt-3 text-slate-700 leading-relaxed">
                The major bottleneck was downloading and displaying an enormous amount
                of HTML. There was no JavaScript in use, and all styles were inline.
            </p>
            <ul class="mt-6 space-y-3 text-slate-700 leading-relaxed list-disc list-inside">
                <li>Moved all styles into a single stylesheet, to be used with the entire application, and stored every value as a CSS variable for easy updating in the future.</li>
                <li>Introduced the DataTables library, which added paging, sorting, and searching in one go.</li>
                <li>The common properties (category, type, access, and active) were made editable in the table itself, reducing the need for the edit screen.</li>
                <li>Removed irrelevant information.</li>
            </ul>

            <h3 class="mt-10 font-medium text-slate-900">Document editing</h3>
            <ul class="mt-6 space-y-3 text-slate-700 leading-relaxed list-disc list-inside">
                <li>The previous interface opened an entirely new window to edit document properties, with all the requisite time and overhead that entailed.
                    I replaced that with a more modern "drawer" interface loaded via ajax.
                </li>
                <li>
                    Document history presented in a tab in the document editing drawer.
                </li>
            </ul>
        </section>

        {{-- Before / after --}}
        <section class="mt-14">
            <h2 class="text-xl font-semibold text-slate-900">Before</h2>
            <div class="mt-6">
                <a href="{{ asset('images/ruco-document-library1.webp') }}" class="glightbox" data-gallery="ruco">
                    <img src="{{ asset('images/ruco-document-library1.webp') }}" alt="Document Library: Before" class="shadowed-image">
                </a>
            </div>

            <h2 class="mt-10 text-xl font-semibold text-slate-900">After</h2>
            <div class="mt-6">
                <a href="{{ asset('images/ruco-document-library2-1.webp') }}" class="glightbox" data-gallery="ruco">
                    <img src="{{ asset('images/ruco-document-library2-1.webp') }}" alt="Document Library: After" class="shadowed-image">
                </a>
            </div>
            <div class="mt-6">
                <a href="{{ asset('images/ruco-document-library2-2.webp') }}" class="glightbox" data-gallery="ruco">
                    <img src="{{ asset('images/ruco-document-library2-2.webp') }}" alt="Document Library: After, Edit Panel" class="shadowed-image">
                </a>
            </div>
        </section>

        {{-- Key decisions --}}
        <section class="mt-14">
            <h2 class="text-xl font-semibold text-slate-900">Key decisions</h2>
            <ul class="mt-6 space-y-6">
                <li>
                    <p class="font-medium text-slate-900">Keep the interface out of the way.</p>
                    <p class="mt-2 text-slate-700 leading-relaxed">Confirmations and status messages stay unobtrusive rather than interrupting the task, so the list itself stays the focus.</p>
                </li>
                <li>
                    <p class="font-medium text-slate-900">Load the list first, fill in the rest with ajax.</p>
                    <p class="mt-2 text-slate-700 leading-relaxed">Cut the initial content load down to just what's needed to show the list, then loaded searching, sorting, and document editing in afterward instead of blocking on all of it up front.</p>
                </li>
                <li>
                    <p class="font-medium text-slate-900">Edit common properties in place.</p>
                    <p class="mt-2 text-slate-700 leading-relaxed">Category, type, access, and active status became editable directly in the table, so most edits never need to open the full document editor at all.</p>
                </li>
            </ul>
        </section>

        {{-- Outcome --}}
        <section class="mt-14 pb-4">
            <h2 class="text-xl font-semibold text-slate-900">Outcome</h2>
            <p class="mt-6 text-slate-700 leading-relaxed">
                Initial page load dropped by roughly 75%, and by 80% once the stylesheet and scripts were
                cached on return visits. Editing a document's properties no longer means leaving the list
                and waiting on a new window; for the properties people change most often, it's now a
                click in the table itself.
            </p>
        </section>

        <div class="pt-8 border-t border-slate-200">
            <a href="{{ route('work') }}" class="text-sm font-medium text-indigo-800 hover:underline">&larr; Back to case studies</a>
        </div>

    </article>
@endsection
