@extends('layouts.app')
@section('title', 'Isi Feedback')
@section('page_title', 'Isi Feedback Harian')

@section('content')
    <div class="card-white max-w-2xl">
        <h2 class="text-xl font-semibold mb-1">Beri Feedback untuk Menu Hari Ini</h2>
        <p class="text-sm text-muted mb-5">{{ now()->translatedFormat('l, d F Y') }} &middot; {{ $menu->sppg?->name }}</p>

        <div class="grid sm:grid-cols-5 gap-2 mb-6">
            @foreach ($menu->slots() as $label => $isi)
                <div class="p-3 rounded-xl bg-primary-light text-center">
                    <p class="text-[10px] uppercase text-primary-dark font-semibold">{{ $label }}</p>
                    <p class="text-xs text-ink mt-1">{{ $isi }}</p>
                </div>
            @endforeach
        </div>

        <form method="POST" action="{{ route('siswa.feedback.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <input type="hidden" name="menu_id" value="{{ $menu->id }}">

            <div>
                <label class="label">Foto Makanan (opsional)</label>
                <input type="file" name="foto" accept="image/jpeg,image/png" class="input bg-white">
                <p class="text-xs text-muted mt-1">Maks 2MB, format JPG atau PNG.</p>
                @error('foto') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="label">Rating</label>
                <div class="star-rating">
                    @for ($i = 5; $i >= 1; $i--)
                        <input type="radio" id="rating-{{ $i }}" name="rating" value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }}>
                        <label for="rating-{{ $i }}">★</label>
                    @endfor
                </div>
                @error('rating') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="label">Komentar (opsional)</label>
                <textarea name="komentar" rows="4" class="input" placeholder="Bagaimana rasa, porsi, dan kondisi makanannya?">{{ old('komentar') }}</textarea>
                @error('komentar') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn-primary">Kirim Feedback</button>
                <a href="{{ route('siswa.dashboard') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
@endsection
