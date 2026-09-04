@extends('layouts.app')

@section('title', 'Work')
@section('description', 'Case studies — process, decisions, and outcomes behind real and sample projects.')

@section('content')
    <section class="max-w-5xl mx-auto px-6 pt-20 pb-4">
        <p class="text-sm font-medium text-indigo-800 mb-4">Work</p>
        <h1 class="mt-2 text-3xl sm:text-4xl font-semibold tracking-tight text-slate-900">
            Case studies
        </h1>
        <p class="mt-6 text-slate-700 leading-relaxed max-w-xl">
            Problem, process, and reasoning behind the work &mdash; not just final screens.
            {# This grid is still filling in; entries marked "Coming soon" are placeholders
            standing in for future case studies. #}
        </p>
    </section>

    <section class="max-w-5xl mx-auto px-6 mt-10 pb-24">
        <div class="grid sm:grid-cols-2 gap-6">
            @foreach ($caseStudies as $cs)
                @if ($cs['placeholder'])
                    <div class="p-6 rounded-lg border border-dashed border-slate-300 bg-slate-50">
                        <div class="flex items-start justify-between gap-4">
                            <h2 class="font-semibold text-slate-500">{{ $cs['title'] }}</h2>
                            <span class="shrink-0 text-xs font-medium uppercase tracking-wide text-slate-500 border border-slate-300 rounded-full px-2 py-1">
                                Coming soon
                            </span>
                        </div>
                        <p class="mt-3 text-sm text-slate-500 leading-relaxed">{{ $cs['summary'] }}</p>
                        <ul class="mt-4 flex flex-wrap gap-2">
                            @foreach ($cs['tags'] as $tag)
                                <li class="px-2.5 py-1 rounded-full text-xs text-slate-500 border border-slate-300">{{ $tag }}</li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <a href="{{ route('work.show', $cs['slug']) }}"
                       class="group p-6 rounded-lg border border-slate-200 hover:border-indigo-800 transition-colors">
                        <h2 class="font-semibold text-slate-900 group-hover:text-indigo-800">{{ $cs['title'] }}</h2>
                        <p class="mt-3 text-sm text-slate-700 leading-relaxed">{{ $cs['summary'] }}</p>
                        <ul class="mt-4 flex flex-wrap gap-2">
                            @foreach ($cs['tags'] as $tag)
                                <li class="px-2.5 py-1 rounded-full text-xs text-slate-700 border border-slate-200">{{ $tag }}</li>
                            @endforeach
                        </ul>
                        <p class="mt-4 text-sm font-medium text-indigo-800 group-hover:underline">Read case study &rarr;</p>
                    </a>
                @endif
            @endforeach
        </div>
    </section>
@endsection
