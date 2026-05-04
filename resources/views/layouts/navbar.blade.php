@php $user = auth()->user(); @endphp
<header class="h-16 bg-white border-b border-bordered px-4 sm:px-8 flex items-center justify-between sticky top-0 z-10">
    <div class="flex items-center gap-3">
        <button id="sidebar-toggle" class="md:hidden p-2 rounded-lg hover:bg-primary-light text-ink">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 6h18M3 12h18M3 18h18"/>
            </svg>
        </button>
        <h1 class="text-base font-semibold text-ink">@yield('page_title', 'DIGILAP MBG')</h1>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 group">
            <div class="text-right hidden sm:block">
                <div class="text-sm font-semibold text-ink leading-tight group-hover:text-primary">{{ $user->name }}</div>
                <div class="text-[11px] text-muted leading-tight uppercase">{{ $user->role }}</div>
            </div>
            <div class="w-9 h-9 rounded-full bg-primary text-white font-semibold flex items-center justify-center text-sm">
                {{ $user->initials }}
            </div>
        </a>
    </div>
</header>
