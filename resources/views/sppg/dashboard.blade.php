@extends('layouts.app')
@section('title', 'Dashboard SPPG')
@section('page_title', 'Dashboard SPPG')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-semibold">{{ auth()->user()->sppg?->name ?? 'SPPG' }}</h2>
        <p class="text-sm text-muted">Pantau feedback siswa hari ini.</p>
    </div>

    <div class="grid sm:grid-cols-3 gap-5 mb-6">
        <div class="card">
            <p class="text-xs text-muted uppercase">Feedback Hari Ini</p>
            <p class="text-3xl font-bold mt-1">{{ $totalToday }}</p>
        </div>
        <div class="card">
            <p class="text-xs text-muted uppercase">Rata-rata Rating</p>
            <p class="text-3xl font-bold mt-1">{{ $avgRatingToday ?? '—' }} <span class="text-sm font-normal text-muted">/5</span></p>
        </div>
        <div class="card flex flex-col">
            <p class="text-xs text-muted uppercase">Status Menu</p>
            <p class="text-base font-semibold mt-1">{{ $sudahInputMenu ? 'Sudah diinput' : 'Belum diinput' }}</p>
            <a href="{{ route('sppg.menu.create') }}"
               class="btn-primary mt-3 {{ $sudahInputMenu ? 'pointer-events-none opacity-50' : '' }}">
                Input Menu Hari Ini
            </a>
        </div>
    </div>

    @if ($menuToday)
        <div class="card-white mb-6">
            <h3 class="text-base font-semibold mb-3">Menu Hari Ini</h3>
            <div class="grid sm:grid-cols-5 gap-2">
                @foreach ($menuToday->slots() as $label => $isi)
                    <div class="p-3 rounded-xl bg-primary-light text-center">
                        <p class="text-[10px] uppercase text-primary-dark font-semibold">{{ $label }}</p>
                        <p class="text-xs text-ink mt-1">{{ $isi }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="card-white">
        <h3 class="text-base font-semibold mb-3">Komentar Terbaru dari Siswa</h3>
        @if ($latestComments->isEmpty())
            <p class="text-sm text-muted py-6 text-center">Belum ada komentar.</p>
        @else
            <div class="divide-y divide-bordered">
                @foreach ($latestComments as $f)
                    <div class="py-3 flex items-start gap-3">
                        <div class="w-9 h-9 rounded-full bg-primary text-white text-xs flex items-center justify-center font-semibold shrink-0">
                            {{ $f->user?->initials }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-sm font-medium">{{ $f->user?->name }}</span>
                                <span class="text-accent text-xs tracking-tighter">
                                    {!! str_repeat('★', $f->rating) . str_repeat('☆', 5 - $f->rating) !!}
                                </span>
                            </div>
                            <p class="text-sm text-muted mt-0.5">{{ $f->komentar }}</p>
                            <p class="text-[11px] text-muted mt-1">{{ $f->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
