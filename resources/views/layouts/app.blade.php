<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DIGILAP MBG')</title>
    <script>
        (function () {
            try {
                var pref = localStorage.getItem('darkMode');
                var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (pref === 'true' || (pref === null && prefersDark)) {
                    document.documentElement.classList.add('dark');
                }
            } catch (_) { /* ignore */ }
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-bg text-ink antialiased">
    <div class="flex min-h-screen">
        @include('layouts.sidebar')

        <div id="sidebar-overlay"
             class="fixed inset-0 bg-black/50 dark:bg-black/70 z-30 hidden lg:hidden"
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

    <div id="deleteModal"
         class="fixed inset-0 z-50 hidden items-center justify-center"
         role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle">
        <div id="deleteModalOverlay" class="absolute inset-0 bg-black/40 dark:bg-black/60 backdrop-blur-sm"></div>
        <div class="relative bg-white dark:bg-[#1A1F1B] rounded-2xl shadow-xl p-6 w-full max-w-sm mx-4 z-10 border border-transparent dark:border-[#2A332C]">
            <div class="text-center">
                <div class="mx-auto w-12 h-12 rounded-full bg-[#FADADD] dark:bg-[#3D1A1F] flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-danger" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2h12a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM5 7a1 1 0 011 1v8a2 2 0 002 2h4a2 2 0 002-2V8a1 1 0 112 0v8a4 4 0 01-4 4H8a4 4 0 01-4-4V8a1 1 0 011-1zm3 3a1 1 0 012 0v6a1 1 0 11-2 0v-6zm4 0a1 1 0 112 0v6a1 1 0 11-2 0v-6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h3 id="deleteModalTitle" class="text-lg font-semibold text-ink dark:text-[#E8EDE9] mb-1">Hapus Data</h3>
                <p id="deleteModalMessage" class="text-sm text-muted dark:text-[#8A9E8D] mb-6">
                    Apakah kamu yakin ingin menghapus data ini?
                </p>
                <div class="flex gap-3">
                    <button type="button" id="deleteModalCancel" class="flex-1 btn-secondary">Batal</button>
                    <form id="deleteModalForm" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger w-full">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const modal   = document.getElementById('deleteModal');
            const form    = document.getElementById('deleteModalForm');
            const msg     = document.getElementById('deleteModalMessage');
            const cancel  = document.getElementById('deleteModalCancel');
            const overlayBg = document.getElementById('deleteModalOverlay');

            function open(action, message) {
                form.action = action;
                msg.textContent = message ||
                    'Apakah kamu yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.';
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.classList.add('overflow-hidden');
            }
            function close() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.classList.remove('overflow-hidden');
            }

            window.confirmDelete = open;
            window.closeDeleteModal = close;

            cancel.addEventListener('click', close);
            overlayBg.addEventListener('click', close);
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) close();
            });
        })();
    </script>

    <script>
        function updateDarkModeIcon(isDark) {
            const moon = document.getElementById('dark-icon-moon');
            const sun = document.getElementById('dark-icon-sun');
            const btn = document.getElementById('dark-mode-toggle');
            if (!moon || !sun) return;
            if (isDark) {
                moon.classList.add('hidden');
                sun.classList.remove('hidden');
                if (btn) btn.title = 'Mode Terang';
            } else {
                moon.classList.remove('hidden');
                sun.classList.add('hidden');
                if (btn) btn.title = 'Mode Gelap';
            }
        }
        function toggleDarkMode() {
            const html = document.documentElement;
            const isDark = html.classList.toggle('dark');
            try { localStorage.setItem('darkMode', isDark); } catch (_) { /* ignore */ }
            updateDarkModeIcon(isDark);
        }
        document.addEventListener('DOMContentLoaded', () => {
            updateDarkModeIcon(document.documentElement.classList.contains('dark'));
        });
    </script>

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
