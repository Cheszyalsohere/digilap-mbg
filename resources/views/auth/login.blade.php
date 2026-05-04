<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - DIGILAP MBG</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-bg flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="w-14 h-14 mx-auto rounded-2xl bg-primary flex items-center justify-center text-white text-2xl font-bold mb-3">D</div>
            <h1 class="text-2xl font-bold text-ink">DIGILAP MBG</h1>
            <p class="text-sm text-muted mt-1">Digitalisasi Laporan Makan Bergizi Gratis</p>
        </div>

        <div class="card-white">
            <h2 class="text-lg font-semibold mb-1">Masuk ke akun Anda</h2>
            <p class="text-sm text-muted mb-5">Gunakan username dan password yang diberikan oleh admin.</p>

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="username" class="label">Username</label>
                    <input id="username" name="username" type="text" autofocus required
                           value="{{ old('username') }}"
                           class="input @error('username') border-danger @enderror"
                           placeholder="contoh: budi_smanba_042">
                    @error('username') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
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
        </div>

        <p class="text-center text-xs text-muted mt-6">
            &copy; {{ date('Y') }} DIGILAP MBG &middot; Demo akun: <code>admin / password</code>
        </p>
    </div>
</body>
</html>
