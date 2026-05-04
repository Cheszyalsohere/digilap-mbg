@extends('layouts.app')
@section('title', 'Input Menu')
@section('page_title', 'Input Menu Hari Ini')

@section('content')
    <div class="card-white max-w-2xl">
        <h2 class="text-xl font-semibold mb-1">Input Menu — {{ now()->translatedFormat('l, d F Y') }}</h2>
        <p class="text-sm text-muted mb-5">Lengkapi 5 slot komponen makanan untuk hari ini.</p>

        <form method="POST" action="{{ route('sppg.menu.store') }}" class="space-y-4">
            @csrf
            @php
                $slots = [
                    'slot_1' => ['Nasi', 'Contoh: Nasi putih 200gr'],
                    'slot_2' => ['Buah', 'Contoh: Pisang ambon 1 buah'],
                    'slot_3' => ['Protein Nabati', 'Contoh: Tempe goreng 2 potong'],
                    'slot_4' => ['Protein Hewani', 'Contoh: Ayam goreng 1 potong'],
                    'slot_5' => ['Susu', 'Contoh: Susu UHT 200ml'],
                ];
            @endphp
            @foreach ($slots as $name => [$label, $placeholder])
                <div>
                    <label class="label">{{ $label }}</label>
                    <input type="text" name="{{ $name }}" value="{{ old($name) }}"
                           class="input @error($name) border-danger @enderror"
                           placeholder="{{ $placeholder }}">
                    @error($name) <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>
            @endforeach

            <div class="flex gap-2 pt-2">
                <button type="submit" class="btn-primary">Simpan Menu</button>
                <a href="{{ route('sppg.dashboard') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
@endsection
