@extends('layouts.app')
@section('title', 'Profil')
@section('page_title', 'Profil Saya')

@section('content')
    <div class="grid md:grid-cols-3 gap-4 sm:gap-5">
        <div class="card text-center">
            <div class="w-20 h-20 mx-auto rounded-full bg-primary text-white flex items-center justify-center text-2xl font-bold">
                {{ $user->initials }}
            </div>
            <h3 class="mt-4 text-base font-semibold break-words">{{ $user->name }}</h3>
            <p class="text-sm text-muted break-all">@<span>{{ $user->username }}</span></p>
            <div class="mt-3 space-y-1 text-sm text-left">
                <p class="break-all"><span class="text-muted">Email:</span> {{ $user->email ?: '—' }}</p>
                <p><span class="text-muted">Role:</span> {{ ucfirst($user->role) }}</p>
                @if ($user->sekolah)
                    <p><span class="text-muted">Sekolah:</span> {{ $user->sekolah }}</p>
                @endif
                @if ($user->sppg)
                    <p><span class="text-muted">SPPG:</span> {{ $user->sppg->name }}</p>
                @endif
            </div>
        </div>

        <div class="card-white md:col-span-2">
            <h3 class="text-base font-semibold mb-4">Edit Profil</h3>
            <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="label">Nama</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="input @error('name') border-danger @enderror">
                    @error('name') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Username</label>
                    @if ($user->isSiswa())
                        <input type="text" name="username" value="{{ old('username', $user->username) }}"
                               minlength="4" maxlength="30" pattern="[a-z0-9_]+"
                               class="input lowercase @error('username') border-danger @enderror">
                        <p class="text-xs text-muted mt-1">
                            Gunakan huruf kecil, angka, dan underscore. Min. 4 karakter.
                        </p>
                        @error('username') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    @else
                        <input type="text" value="{{ $user->username }}" class="input bg-surface text-muted cursor-not-allowed" readonly>
                        <p class="text-xs text-muted mt-1">Username untuk role {{ ucfirst($user->role) }} tidak dapat diubah.</p>
                    @endif
                </div>
                <div>
                    <label class="label">Email <span class="text-muted font-normal">(opsional)</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="input @error('email') border-danger @enderror">
                    @error('email') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="label">Password Baru</label>
                        <input type="password" name="password" class="input">
                        @error('password') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="input">
                    </div>
                </div>

                @if ($user->isSiswa())
                    <div class="pt-4 border-t border-bordered dark:border-[#2A332C]">
                        <label class="label flex items-center gap-2">
                            <span aria-hidden="true">🥜</span>
                            <span>Informasi Alergi</span>
                        </label>
                        <p class="text-xs text-muted mb-3">
                            Centang alergi yang kamu miliki. SPPG akan menggunakan info ini untuk menyiapkan menu alternatif.
                        </p>
                        <div class="space-y-2">
                            @foreach ($allergies as $allergy)
                                @php
                                    $checked = collect(old('allergies', $userAllergyIds))->contains($allergy->id);
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
                                               value="{{ old('allergy_lainnya', $userAllergyLainnya) }}"
                                               maxlength="255"
                                               placeholder="Tuliskan jenis alergimu"
                                               class="input">
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                <button type="submit" class="btn-primary w-full sm:w-auto">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    @if ($user->isSiswa())
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
    @endif
@endsection
