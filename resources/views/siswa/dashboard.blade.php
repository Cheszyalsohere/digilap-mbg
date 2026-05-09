@extends('layouts.app')
@section('title', 'Dashboard Siswa')
@section('page_title', 'Dashboard')

@section('content')
    <div class="mb-6">
        <h2 class="text-xl sm:text-2xl font-semibold">Halo, {{ auth()->user()->name }}</h2>
        <p class="text-sm text-muted">Cek menu hari ini dan beri feedback agar program MBG semakin baik.</p>
    </div>

    <div class="grid md:grid-cols-2 gap-4 sm:gap-5 mb-6">
        <div class="card">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <p class="text-xs text-muted uppercase tracking-wide">Menu Hari Ini</p>
                    <h3 class="text-lg font-semibold mt-0.5">{{ now()->translatedFormat('l, d F Y') }}</h3>
                </div>
                <span class="badge-info">{{ auth()->user()->sppg?->name ?? '-' }}</span>
            </div>
            @if ($menuToday)
                <ul class="space-y-2 text-sm">
                    @foreach ($menuToday->slots() as $label => $isi)
                        <li class="flex justify-between gap-3">
                            <span class="text-muted">{{ $label }}</span>
                            <span class="font-medium text-right">{{ $isi }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-muted py-4">Menu belum tersedia hari ini.</p>
            @endif
        </div>

        <div class="card flex flex-col">
            <p class="text-xs text-muted uppercase tracking-wide">Status Feedback</p>
            <h3 class="text-lg font-semibold mt-0.5">
                {{ $sudahFeedback ? 'Sudah diisi' : ($menuToday ? 'Belum diisi' : 'Menu belum ada') }}
            </h3>
            <p class="text-sm text-muted mt-2">
                @if ($sudahFeedback)
                    Terima kasih sudah mengisi feedback untuk hari ini.
                @elseif ($menuToday)
                    Pastikan kamu mengisi feedback agar SPPG bisa memperbaiki kualitas makanan.
                @else
                    Tunggu menu hari ini diinput oleh SPPG.
                @endif
            </p>
            <div class="mt-auto pt-4">
                <a href="{{ route('siswa.feedback.create') }}"
                   class="btn-primary w-full justify-center {{ $sudahFeedback || ! $menuToday ? 'pointer-events-none opacity-50' : '' }}">
                    Isi Feedback Hari Ini
                </a>
            </div>
        </div>
    </div>

    <div class="card-white">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-base font-semibold">Riwayat 7 Hari Terakhir</h3>
            <a href="{{ route('siswa.feedback.index') }}" class="text-sm text-primary hover:underline">Lihat semua</a>
        </div>
        @if ($riwayat->isEmpty())
            <p class="text-sm text-muted py-6 text-center">Belum ada feedback dalam 7 hari terakhir.</p>
        @else
            <div class="divide-y divide-bordered">
                @foreach ($riwayat as $f)
                    <div class="py-3 flex items-center gap-3">
                        @if ($f->foto)
                            <img src="{{ asset('storage/' . $f->foto) }}" alt="" class="w-12 h-12 rounded-lg object-cover">
                        @else
                            <div class="w-12 h-12 rounded-lg bg-primary-light flex items-center justify-center text-primary text-xs">No Foto</div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium">{{ $f->created_at->translatedFormat('l, d M Y') }}</div>
                            <div class="text-xs text-muted truncate">{{ $f->komentar ?: '— tanpa komentar —' }}</div>
                        </div>
                        <div class="text-accent text-sm tracking-tighter">
                            {!! str_repeat('★', $f->rating) . str_repeat('☆', 5 - $f->rating) !!}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
