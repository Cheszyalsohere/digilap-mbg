@php
    $user = auth()->user();
    $unread = \App\Models\Chat::where('receiver_id', $user->id)->where('is_read', false)->count();
@endphp

<aside id="app-sidebar"
       class="hidden md:flex md:flex-col w-60 bg-white border-r border-bordered h-screen sticky top-0 shrink-0">
    <div class="px-6 py-5 border-b border-bordered">
        <a href="/" class="flex items-center gap-2">
            <div class="w-9 h-9 rounded-xl bg-primary flex items-center justify-center text-white font-bold">D</div>
            <div>
                <div class="font-bold text-ink leading-none">DIGILAP</div>
                <div class="text-[11px] text-muted leading-none mt-0.5">MBG Monitoring</div>
            </div>
        </a>
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
