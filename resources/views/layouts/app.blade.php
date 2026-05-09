<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DIGILAP MBG')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-bg text-ink antialiased">
    <div class="flex min-h-screen">
        @include('layouts.sidebar')

        <div id="sidebar-overlay"
             class="fixed inset-0 bg-black/50 z-30 hidden lg:hidden"
             aria-hidden="true"></div>

        <div class="flex-1 flex flex-col min-w-0">
            @include('layouts.navbar')

            <main class="flex-1 px-4 sm:px-6 lg:px-8 py-5 sm:py-6 max-w-[1200px] w-full mx-auto">
                @if (session('success'))
                    <div class="mb-4 px-4 py-3 rounded-xl bg-[#D5F0E0] text-[#1E7A45] text-sm">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 px-4 py-3 rounded-xl bg-[#FADADD] text-[#922B21] text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        (function () {
            const sidebar  = document.getElementById('app-sidebar');
            const overlay  = document.getElementById('sidebar-overlay');
            const openBtn  = document.getElementById('sidebar-toggle');
            const closeBtn = document.getElementById('sidebar-close');
            if (!sidebar || !overlay) return;

            const open = () => {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden', 'lg:overflow-auto');
            };
            const close = () => {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden', 'lg:overflow-auto');
            };

            openBtn  && openBtn.addEventListener('click', open);
            closeBtn && closeBtn.addEventListener('click', close);
            overlay.addEventListener('click', close);
            document.addEventListener('keydown', (e) => { if (e.key === 'Escape') close(); });

            // close drawer when navigating to a new link inside sidebar (mobile)
            sidebar.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
                if (window.innerWidth < 1024) close();
            }));

            // reset on resize so desktop never gets the closed state
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024) {
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden', 'lg:overflow-auto');
                } else {
                    sidebar.classList.add('-translate-x-full');
                }
            });
        })();
    </script>
    @stack('scripts')
</body>
</html>
