@php $user = auth()->user(); @endphp
<header class="h-16 bg-white border-b border-bordered px-3 sm:px-6 lg:px-8 flex items-center justify-between sticky top-0 z-20">
    <div class="flex items-center gap-2 sm:gap-3 min-w-0 flex-1">
        <button id="sidebar-toggle" type="button"
                class="lg:hidden p-2 -ml-1 rounded-lg hover:bg-primary-light text-ink shrink-0"
                aria-label="Buka menu">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 6h18M3 12h18M3 18h18"/>
            </svg>
        </button>
        <h1 class="text-base sm:text-lg font-semibold text-ink truncate">@yield('page_title', 'DIGILAP MBG')</h1>
    </div>
    <div class="flex items-center gap-3 shrink-0">
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
