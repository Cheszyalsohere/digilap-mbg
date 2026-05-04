@extends('layouts.app')
@section('title', 'Profil')
@section('page_title', 'Profil Saya')

@section('content')
    <div class="grid md:grid-cols-3 gap-5">
        <div class="card text-center">
            <div class="w-20 h-20 mx-auto rounded-full bg-primary text-white flex items-center justify-center text-2xl font-bold">
                {{ $user->initials }}
            </div>
            <h3 class="mt-4 text-base font-semibold">{{ $user->name }}</h3>
            <p class="text-sm text-muted">@<span>{{ $user->username }}</span></p>
            <div class="mt-3 space-y-1 text-sm text-left">
                <p><span class="text-muted">Email:</span> {{ $user->email }}</p>
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
                    <label class="label">Email</label>
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
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>
@endsection
