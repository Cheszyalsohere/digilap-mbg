@php
    $user = auth()->user();
    $unread = \App\Models\Chat::where('receiver_id', $user->id)->where('is_read', false)->count();
@endphp

<aside id="app-sidebar"
       class="fixed lg:sticky top-0 left-0 z-40 w-60 bg-white dark:bg-[#1A1F1B] border-r border-bordered dark:border-[#2A332C] h-screen shrink-0
              flex flex-col transform -translate-x-full lg:translate-x-0 transition-transform duration-200">
    <div class="px-3 py-3 border-b border-bordered dark:border-[#2A332C] flex items-center justify-between">
        <a href="/" class="flex items-center bg-white rounded-lg p-1">
            <img src="{{ asset('images/logo-digilap.png') }}"
                 alt="DIGILAP MBG"
                 style="height: 80px; width: auto;">
        </a>
        <button id="sidebar-close" type="button"
                class="lg:hidden p-2 -mr-2 rounded-lg hover:bg-primary-light text-muted hover:text-ink"
                aria-label="Tutup menu">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M6 6l12 12M18 6L6 18"/>
            </svg>
        </button>
    </div>

    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto text-sm">
        @if ($user->isSiswa())
            <a href="{{ route('siswa.dashboard') }}"
               class="nav-link {{ request()->routeIs('siswa.dashboard') ? 'nav-link-active' : '' }}">
                <span>Dashboard</span>
            </a>
            <a href="{{ route('siswa.menu') }}"
               class="nav-link {{ request()->routeIs('siswa.menu') ? 'nav-link-active' : '' }}">
                <span>Menu Hari Ini</span>
            </a>
            <a href="{{ route('siswa.feedback.index') }}"
               class="nav-link {{ request()->routeIs('siswa.feedback.*') ? 'nav-link-active' : '' }}">
                <span>Riwayat Feedback</span>
            </a>
        @endif

        @if ($user->isSppg())
            <a href="{{ route('sppg.dashboard') }}"
               class="nav-link {{ request()->routeIs('sppg.dashboard') ? 'nav-link-active' : '' }}">
                <span>Dashboard</span>
            </a>
            <a href="{{ route('sppg.menu.create') }}"
               class="nav-link {{ request()->routeIs('sppg.menu.*') ? 'nav-link-active' : '' }}">
                <span>Input Menu</span>
            </a>
            <a href="{{ route('sppg.laporan') }}"
               class="nav-link {{ request()->routeIs('sppg.laporan') ? 'nav-link-active' : '' }}">
                <span>Laporan Feedback</span>
            </a>
        @endif

        @if ($user->isAdmin())
            <a href="{{ route('admin.dashboard') }}"
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'nav-link-active' : '' }}">
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.analisis') }}"
               class="nav-link {{ request()->routeIs('admin.analisis') ? 'nav-link-active' : '' }}">
                <span>Analisis Penyimpangan</span>
            </a>
            <a href="{{ route('admin.users.index') }}"
               class="nav-link {{ request()->routeIs('admin.users.*') ? 'nav-link-active' : '' }}">
                <span>Manajemen User</span>
            </a>
            <a href="{{ route('admin.invitation-codes.index') }}"
               class="nav-link {{ request()->routeIs('admin.invitation-codes.*') ? 'nav-link-active' : '' }}">
                <span>Kode Undangan</span>
            </a>
            <a href="{{ route('admin.activity-logs') }}"
               class="nav-link {{ request()->routeIs('admin.activity-logs') ? 'nav-link-active' : '' }}">
                <span>Log Aktivitas</span>
            </a>
        @endif

        <div class="my-3 border-t border-bordered"></div>

        <a href="{{ route('chat.index') }}"
           class="nav-link justify-between {{ request()->routeIs('chat.*') ? 'nav-link-active' : '' }}">
            <span>Chat</span>
            @if ($unread > 0)
                <span class="px-2 py-0.5 rounded-full bg-accent text-white text-[10px] font-bold">{{ $unread }}</span>
            @endif
        </a>
        <a href="{{ route('profile.edit') }}"
           class="nav-link {{ request()->routeIs('profile.*') ? 'nav-link-active' : '' }}">
            <span>Profil</span>
        </a>
        <a href="{{ route('about') }}"
           class="nav-link {{ request()->routeIs('about') ? 'nav-link-active' : '' }}">
            <span>Tentang</span>
        </a>
    </nav>

    <div class="px-3 py-4 border-t border-bordered">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link w-full text-danger hover:bg-[#FADADD] hover:text-[#922B21]">
                Logout
            </button>
        </form>
    </div>
</aside>
