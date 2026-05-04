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

        <div class="flex-1 flex flex-col min-w-0">
            @include('layouts.navbar')

            <main class="flex-1 px-4 sm:px-8 py-6 max-w-[1200px] w-full mx-auto">
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
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('app-sidebar');
        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', () => sidebar.classList.toggle('hidden'));
        }
    </script>
    @stack('scripts')
</body>
</html>
