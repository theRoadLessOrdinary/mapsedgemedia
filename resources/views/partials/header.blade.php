@php
    $navLinks = [
        'home' => ['label' => 'Home', 'route' => 'home'],
        'about' => ['label' => 'About', 'route' => 'about'],
        'work' => ['label' => 'Work', 'route' => 'work'],
        'contact' => ['label' => 'Contact', 'route' => 'contact'],
    ];
@endphp

<header class="border-b border-slate-200">
    <div class="max-w-5xl mx-auto px-6 py-5 flex items-center justify-between gap-6">
        <a href="{{ route('home') }}" class="flex items-center gap-2 font-semibold tracking-tight text-lg">
            <span class="inline-block w-2.5 h-2.5 rounded-full bg-indigo-800" aria-hidden="true"></span>
            Maps Edge Media
        </a>

        <nav aria-label="Primary">
            <ul class="flex items-center gap-1 text-sm">
                @foreach ($navLinks as $key => $link)
                    @php $isActive = request()->routeIs($link['route']); @endphp
                    <li>
                        <a
                            href="{{ route($link['route']) }}"
                            @if ($isActive) aria-current="page" @endif
                            class="px-3 py-2 rounded-md transition-colors {{ $isActive ? 'text-indigo-900 font-medium bg-indigo-50' : 'text-slate-700 hover:text-slate-900 hover:bg-slate-50' }}"
                        >
                            {{ $link['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
    </div>
</header>
