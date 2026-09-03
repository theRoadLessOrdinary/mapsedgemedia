@extends('layouts.app')

@section('title', 'Contact')
@section('description', 'Get in touch with William Morris.')

@section('content')
    <section class="max-w-3xl mx-auto px-6 pt-20 pb-24">
        <p class="text-sm font-medium text-indigo-800 mb-4">Contact</p>
        <h1 class="mt-2 text-3xl sm:text-4xl font-semibold tracking-tight text-slate-900">
            Let's talk
        </h1>
        <p class="mt-6 text-slate-700 leading-relaxed max-w-xl">
            The fastest way to reach me is email.
        </p>
        <div class="mt-8 flex flex-col gap-3 text-slate-700">
            <a href="mailto:billTheTailor@gmail.com">billTheTailor@gmail.com</a>
            <a href="https://www.linkedin.com/in/bill-morris-b380aba/" target="_blank" rel="noopener">LinkedIn</a>
            <a href="https://github.com/theroadlessordinary" target="_blank" rel="noopener">GitHub</a>
        </div>
    </section>
@endsection
