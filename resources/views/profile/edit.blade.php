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
                <button type="submit" class="btn-primary w-full sm:w-auto">Simpan Perubahan</button>
            </form>
        </div>
    </div>
@endsection
