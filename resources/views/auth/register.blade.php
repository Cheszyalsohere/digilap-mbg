<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - DIGILAP MBG</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-bg flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="w-14 h-14 mx-auto rounded-2xl bg-primary flex items-center justify-center text-white text-2xl font-bold mb-3">D</div>
            <h1 class="text-2xl font-bold text-ink">Daftar Akun Siswa</h1>
            <p class="text-sm text-muted mt-1">Gunakan kode undangan dari sekolahmu.</p>
        </div>

        <div class="card-white">
            <h2 class="text-lg font-semibold mb-1">Buat akun baru</h2>
            <p class="text-sm text-muted mb-5">Kode undangan diberikan oleh admin atau pihak sekolah.</p>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="label">Nama Lengkap</label>
                    <input id="name" name="name" type="text" autofocus required
                           value="{{ old('name') }}"
                           class="input @error('name') border-danger @enderror"
                           placeholder="contoh: Budi Santoso">
                    @error('name') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="code" class="label">Kode Undangan</label>
                    <input id="code" name="code" type="text" required
                           value="{{ old('code') }}"
                           class="input uppercase tracking-wider @error('code') border-danger @enderror"
                           placeholder="contoh: SMANBA-X7K2">
                    @error('code') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="label">Password</label>
                    <input id="password" name="password" type="password" required minlength="8"
                           class="input @error('password') border-danger @enderror">
                    <p class="text-xs text-muted mt-1">Minimal 8 karakter.</p>
                    @error('password') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="label">Konfirmasi Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8"
                           class="input">
                </div>

                <button type="submit" class="btn-primary w-full justify-center">Daftar</button>
            </form>
        </div>

        <p class="text-center text-sm text-muted mt-6">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-primary font-semibold hover:underline">Masuk di sini</a>
        </p>
        <p class="text-center text-xs mt-2">
            <a href="{{ route('about') }}" class="text-primary hover:underline">Tentang DIGILAP MBG</a>
        </p>
    </div>
</body>
</html>
