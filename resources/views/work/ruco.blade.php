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
                <dd class="mt-1 text-slate-900 font-medium">Solo, wwith four administrative stakeholders</dd>
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
                Over the years, the company had accummulated several hundred documents, are fair nuber of which were in at least weekly use.
                The list was built as just that: a simple list of links pointing to stored PDFs and .doc files. The searching was limited 
                and took up otherwise usable space. The list was crowded, including information that was not relevant to the end user.
                If a document was uploaded with a duplicate name, the old file was simply overwritten without confirmation, making information
                loss a genuine risk. There was only one version of each file, no history available.
            </p>
        </section>

        {{-- Process --}}
        <section class="mt-14">
            <h2 class="text-xl font-semibold text-slate-900">Process</h2>
            <p class="mt-6 text-slate-700 leading-relaxed">
                <b>Page load time</b>. The major bottleneck was downloading and displaying an enormous amount
                of HTML. There was no javascript in use, and all styles were inline. 
            </p>
            <ul class="mt-6 space-y-3 text-slate-700 leading-relaxed list-disc list-inside">
                <li>Moved all styles into a single stylesheet, to be used with the entire application, and stored every value as a CSS variable for easy updating in the future.</li>
                <li>Introduced the DataTables library, which added paging, sorting, and searching in one go.</li>
                <li>The common properties (category, type, access, and active) where made editable in the table itself, reducing the need for the edit screen.</li>
                <li>Removed irrelevant information.</li>
                <li>Reduced the initial page load by roughly 75%, to 80% after the external files were cached.</li>
            </ul>
            <p class="mt-6 text-slate-700 leading-relaxed">
                <b>Document Editing</b>.
            </p>
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
            &nbsp;
            <a href="{{ asset('images/ruco-document-library1.webp') }}" class="glightbox" data-gallery="ruco">
            <img src="{{ asset('images/ruco-document-library1.webp') }}" alt="Document Library: Before" class="shadowed-image">
            </a>
            <p>&nbsp;</p>

            <h2 class="text-xl font-semibold text-slate-900">After</h2>
                &nbsp;
                <p>
                    <a href="{{ asset('images/ruco-document-library2-1.webp') }}" class="glightbox" data-gallery="ruco">
                        <img src="{{ asset('images/ruco-document-library2-1.webp') }}" alt="Document Library: Before" class="shadowed-image">
                    </a>
                </p>
                &nbsp;
                <p>
                    <a href="{{ asset('images/ruco-document-library2-2.webp') }}" class="glightbox" data-gallery="ruco">
                    <img src="{{ asset('images/ruco-document-library2-2.webp') }}" alt="Document Library: Before, Edit Panel" class="shadowed-image">
                    </a>
                </p>

        </section>

        {{-- Key decisions --}}
        <section class="mt-14">
            <h2 class="text-xl font-semibold text-slate-900">Key decisions</h2>
            <ul class="mt-6 space-y-6">
                <li>
                    <p class="font-medium text-slate-900">Keep the interface out of the way: unobtrusive confirmations and status messages; 
                        searching and sorting easily available; document editing simplified.</p>
                </li>
                <li>
                    <p class="font-medium text-slate-900">
                        Speed up the interface by reducing the initial
                    </p>
                </li>
            </ul>
        </section>

        {{-- Outcome --}}
        <section class="mt-14 pb-4">
            <h2 class="text-xl font-semibold text-slate-900">Outcome</h2>
            <p class="mt-6 text-slate-700 leading-relaxed">
                
            </p>
        </section>

        <div class="pt-8 border-t border-slate-200">
            <a href="{{ route('work') }}" class="text-sm font-medium text-indigo-800 hover:underline">&larr; Back to case studies</a>
        </div>

    </article>
@endsection
