@extends('layouts.app')

@section('title', 'Home')
@section('description', 'William Morris — UI/UX-focused developer. Case studies, real product work, and process.')

@section('content')
    <section class="max-w-5xl mx-auto px-6 pt-20 pb-16">
        <div class="grid sm:grid-cols-[1fr_auto] gap-12 items-center">
            <div>
                <p class="text-sm font-medium text-indigo-800 mb-4">UI/UX &amp; Full-Stack Developer</p>
                <h1 class="text-4xl sm:text-5xl font-semibold tracking-tight text-slate-900 max-w-2xl">
                    I design and build software people actually enjoy using.
                </h1>
                <p class="mt-6 text-lg text-slate-700 max-w-xl leading-relaxed">
                    Over 20 years turning legacy systems and rough ideas into interfaces that are clear,
                    fast, and accessible &mdash; from e-commerce platforms to internal tools. I care as much
                    about the process as the pixels.
                </p>
                <div class="mt-10 flex flex-wrap items-center gap-4">
                    <a href="{{ route('work') }}"
                       class="inline-flex items-center px-5 py-3 rounded-md bg-indigo-800 text-white font-medium hover:bg-indigo-900 transition-colors">
                        View case studies
                    </a>
                    <a href="{{ route('about') }}"
                       class="inline-flex items-center px-5 py-3 rounded-md text-slate-700 font-medium hover:bg-slate-50 transition-colors">
                        About me
                    </a>
                </div>
            </div>

            <img
                src="{{ asset('images/william-morris.webp') }}"
                alt="Portrait of William Morris"
                width="240"
                height="240"
                class="w-40 h-40 sm:w-60 sm:h-60 rounded-full object-cover border border-slate-200 justify-self-center sm:justify-self-end"
            >
        </div>
    </section>

    <section class="max-w-5xl mx-auto px-6 mt-6 pb-24">
        <div class="grid sm:grid-cols-3 gap-6">
            <div class="p-6 rounded-lg border border-slate-200">
                <h2 class="font-semibold text-slate-900">Product thinking</h2>
                <p class="mt-2 text-sm text-slate-700 leading-relaxed">
                    Founder of an e-commerce platform built from the ground up &mdash; I've owned
                    the full loop from customer problem to shipped feature.
                </p>
            </div>
            <div class="p-6 rounded-lg border border-slate-200">
                <h2 class="font-semibold text-slate-900">Accessible by default</h2>
                <p class="mt-2 text-sm text-slate-700 leading-relaxed">
                    Clear hierarchy, predictable interaction patterns, and real WCAG-minded
                    accessibility &mdash; not bolted on at the end.
                </p>
            </div>
            <div class="p-6 rounded-lg border border-slate-200">
                <h2 class="font-semibold text-slate-900">Full-stack fluency</h2>
                <p class="mt-2 text-sm text-slate-700 leading-relaxed">
                    Comfortable owning the interface and the system behind it &mdash; PHP, MySQL,
                    SQL Server, and everything in between.
                </p>
            </div>
        </div>
    </section>
@endsection
