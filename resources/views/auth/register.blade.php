<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-digilap.png') }}">
    <title>Daftar - DIGILAP MBG</title>
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
<body class="min-h-screen bg-bg flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo-digilap.png') }}"
                 alt="DIGILAP MBG"
                 class="mx-auto mb-4"
                 style="height: 200px; width: auto;">
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

                <div class="pt-3 border-t border-bordered dark:border-[#2A332C]">
                    <label class="label flex items-center gap-2">
                        <span aria-hidden="true">🥜</span>
                        <span>Informasi Alergi <span class="font-normal text-muted">(Opsional)</span></span>
                    </label>
                    <p class="text-xs text-muted mb-3">
                        Centang jika kamu memiliki alergi makanan. Informasi ini membantu SPPG menyiapkan menu yang aman untukmu.
                    </p>
                    <div class="space-y-2">
                        @foreach ($allergies as $allergy)
                            @php
                                $checked = collect(old('allergies', []))->contains($allergy->id);
                                $isLainnya = $allergy->slug === 'lainnya';
                            @endphp
                            <label class="flex items-start gap-2 text-sm">
                                <input type="checkbox" name="allergies[]" value="{{ $allergy->id }}"
                                       @if ($isLainnya) data-allergy-lainnya @endif
                                       {{ $checked ? 'checked' : '' }}
                                       class="mt-0.5 rounded border-bordered text-primary focus:ring-primary">
                                <span>{{ $allergy->name }}</span>
                            </label>
                            @if ($isLainnya)
                                <div id="allergy-lainnya-wrap" class="{{ $checked ? '' : 'hidden' }} pl-6">
                                    <input type="text" name="allergy_lainnya"
                                           value="{{ old('allergy_lainnya') }}"
                                           maxlength="255"
                                           placeholder="Tuliskan jenis alergimu"
                                           class="input">
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="btn-primary w-full justify-center">Daftar</button>
            </form>

            <script>
                (function () {
                    const cb = document.querySelector('[data-allergy-lainnya]');
                    const wrap = document.getElementById('allergy-lainnya-wrap');
                    if (!cb || !wrap) return;
                    cb.addEventListener('change', () => {
                        wrap.classList.toggle('hidden', !cb.checked);
                        if (!cb.checked) {
                            const input = wrap.querySelector('input');
                            if (input) input.value = '';
                        }
                    });
                })();
            </script>
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
