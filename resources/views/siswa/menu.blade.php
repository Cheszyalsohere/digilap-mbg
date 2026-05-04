@extends('layouts.app')
@section('title', 'Menu Hari Ini')
@section('page_title', 'Menu Hari Ini')

@section('content')
    <div class="card-white max-w-2xl">
        <div class="flex items-start justify-between mb-4">
            <div>
                <p class="text-xs text-muted uppercase tracking-wide">Menu</p>
                <h2 class="text-xl font-semibold">{{ now()->translatedFormat('l, d F Y') }}</h2>
            </div>
            @if ($menuToday)
                <span class="badge-info">{{ $menuToday->sppg?->name }}</span>
            @endif
        </div>

        @if ($menuToday)
            <div class="grid sm:grid-cols-2 gap-3">
                @foreach ($menuToday->slots() as $label => $isi)
                    <div class="p-4 rounded-xl bg-primary-light">
                        <p class="text-[11px] uppercase text-primary-dark tracking-wide font-semibold">{{ $label }}</p>
                        <p class="text-sm text-ink mt-1">{{ $isi }}</p>
                    </div>
                @endforeach
            </div>
            <div class="mt-6">
                <a href="{{ route('siswa.feedback.create') }}" class="btn-primary">Isi Feedback Hari Ini</a>
            </div>
        @else
            <p class="text-sm text-muted py-8 text-center">Menu belum tersedia hari ini.</p>
        @endif
    </div>
@endsection
