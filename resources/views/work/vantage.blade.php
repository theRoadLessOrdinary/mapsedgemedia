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
                <dd class="mt-1 text-slate-900 font-medium">Web applications developer</dd>
            </div>
            <div>
                <dt class="text-slate-500 uppercase tracking-wide text-xs">Timeline</dt>
                <dd class="mt-1 text-slate-900 font-medium">Apr 2023 &ndash; Jan 2026</dd>
            </div>
            <div>
                <dt class="text-slate-500 uppercase tracking-wide text-xs">Team</dt>
                <dd class="mt-1 text-slate-900 font-medium">Solo, with dispatch staff as stakeholders</dd>
            </div>
            <div>
                <dt class="text-slate-500 uppercase tracking-wide text-xs">Tools</dt>
                <dd class="mt-1 text-slate-900 font-medium">Classic ASP, PHP, JavaScript</dd>
            </div>
        </dl>

        <section class="mt-14">
            <p class="text-slate-700 leading-relaxed">
                The recreation below is a static snapshot &mdash; its markup and layout reflect
                the real search dashboard, but it runs on sample data, not a live database.
                The filter controls (Trade / Co-app / Printable, and the four Sort fields) use
                a custom cycling roller control in place of standard dropdowns &mdash; click any
                of them to see it roll.
            </p>
        </section>

        <section class="mt-10 w-screen relative left-1/2 right-1/2 -mx-[50vw] px-6">
            <div class="max-w-[1600px] mx-auto overflow-x-auto rounded-lg border border-slate-200">
                <iframe
                    src="{{ asset('case-studies/vantage/dashboard.html') }}"
                    title="Vantage Finance dashboard recreation"
                    style="width: 1400px; height: 900px; display: block;"
                    loading="lazy"
                ></iframe>
            </div>
        </section>

        <div class="pt-8 mt-14 border-t border-slate-200">
            <a href="{{ route('work') }}" class="text-sm font-medium text-indigo-800 hover:underline">&larr; Back to case studies</a>
        </div>

    </article>
@endsection
