<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-digilap.png') }}">
    <title>Login - DIGILAP MBG</title>
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
</head>
<body class="min-h-screen bg-bg flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo-digilap.png') }}"
                 alt="DIGILAP MBG"
                 class="mx-auto mb-4"
                 style="height: 200px; width: auto;">
            <p class="text-sm text-muted mt-1">Digitalisasi Laporan Makan Bergizi Gratis</p>
        </div>

        <div class="card-white">
            <h2 class="text-lg font-semibold mb-1">Masuk ke akun Anda</h2>
            <p class="text-sm text-muted mb-5">Gunakan username dan password yang diberikan oleh admin.</p>

            @if (session('login_lockout'))
                <div class="mb-4 p-3 rounded-xl bg-[#FADADD] text-[#922B21] text-sm flex items-start gap-2">
                    <span aria-hidden="true">⚠</span>
                    <span>{{ $errors->first('username') }}</span>
                </div>
            @elseif (session('login_remaining'))
                <div class="mb-4 p-3 rounded-xl bg-[#FDF3DC] text-[#8A6620] text-sm flex items-start gap-2">
                    <span aria-hidden="true">⚠</span>
                    <span>{{ $errors->first('username') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="username" class="label">Username</label>
                    <input id="username" name="username" type="text" autofocus required
                           value="{{ old('username') }}"
                           class="input @error('username') border-danger @enderror"
                           placeholder="contoh: budi_smanba_042">
                    @if ($errors->has('username') && ! session('login_lockout') && ! session('login_remaining'))
                        <p class="text-xs text-danger mt-1">{{ $errors->first('username') }}</p>
                    @endif
                </div>

                <div>
                    <label for="password" class="label">Password</label>
                    <input id="password" name="password" type="password" required
                           class="input @error('password') border-danger @enderror">
                    @error('password') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>

                <label class="flex items-center gap-2 text-sm text-muted">
                    <input type="checkbox" name="remember" class="rounded border-bordered text-primary focus:ring-primary">
                    Ingat saya
                </label>

                <button type="submit" class="btn-primary w-full justify-center">Masuk</button>
            </form>

            <p class="text-center text-sm text-muted mt-5 pt-4 border-t border-bordered dark:border-[#2A332C]">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-primary font-semibold hover:underline">Daftar pakai kode undangan</a>
            </p>
        </div>

        <p class="text-center text-xs text-muted mt-6">
            &copy; {{ date('Y') }} DIGILAP MBG &middot; Demo akun: <code>admin / password</code>
        </p>
        <p class="text-center text-xs mt-2">
            <a href="{{ route('about') }}" class="text-primary hover:underline">Tentang DIGILAP MBG</a>
        </p>
    </div>
</body>
</html>
