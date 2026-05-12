@extends('layouts.app')
@section('title', 'Menu Hari Ini')
@section('page_title', 'Menu Hari Ini')

@section('content')
    @php $user = auth()->user(); @endphp
    <div class="card-white max-w-2xl">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2 mb-4">
            <div class="min-w-0">
                <p class="text-xs text-muted uppercase tracking-wide">Menu</p>
                <h2 class="text-lg sm:text-xl font-semibold break-words">{{ now()->translatedFormat('l, d F Y') }}</h2>
            </div>
            @if ($menuToday)
                <span class="badge-info self-start">{{ $menuToday->sppg?->name }}</span>
            @endif
        </div>

        @if ($menuToday)
            @if ($menuView['is_alternatif'])
                <div class="mb-4 p-4 rounded-xl bg-primary-light dark:bg-[#1A2E23] border-l-4 border-primary">
                    <p class="text-sm font-semibold flex items-center gap-1.5">
                        <span aria-hidden="true">🌿</span>
                        Kamu mendapatkan menu alternatif
                    </p>
                    <p class="text-xs text-muted mt-1">
                        Karena kamu memiliki alergi: <span class="font-medium text-ink dark:text-[#E8EDE9]">{{ $user->allergyNames() }}</span>.
                        @if (! empty($menuView['keterangan']))
                            <span class="block mt-1">{{ $menuView['keterangan'] }}</span>
                        @endif
                    </p>
                </div>
            @elseif ($user->hasAllergy() && ! $menuToday->has_alternatif)
                <div class="mb-4 p-4 rounded-xl bg-[#FDF3DC] dark:bg-[#3D3320] border-l-4 border-warning">
                    <p class="text-sm font-semibold text-[#8A6620] dark:text-[#E8C778] flex items-center gap-1.5">
                        <span aria-hidden="true">⚠️</span>
                        Perhatian: Cek kandungan menu hari ini
                    </p>
                    <p class="text-xs text-[#8A6620] dark:text-[#E8C778] mt-1">
                        Menu hari ini mungkin mengandung bahan yang tidak sesuai dengan alergimu ({{ $user->allergyNames() }}).
                        Hubungi SPPG untuk informasi lebih lanjut.
                    </p>
                </div>
            @endif

            @if ($menuToday->foto_menu)
                <img src="{{ asset('storage/' . $menuToday->foto_menu) }}"
                     alt="Foto menu hari ini"
                     class="w-full max-h-[300px] object-cover rounded-2xl border border-bordered mb-4">
            @else
                <div class="w-full h-44 rounded-2xl bg-primary-light flex flex-col items-center justify-center text-primary-dark mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mb-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a4 4 0 100-8 4 4 0 000 8z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-xs font-medium">Foto menu belum tersedia</p>
                </div>
            @endif
            <div class="grid grid-cols-2 gap-3">
                @foreach ($menuView['slots'] as $isi)
                    @if ($isi)
                        <div class="bg-primary-light rounded-xl px-4 py-3 text-sm font-medium text-center border border-primary text-primary-dark dark:bg-[#1A2E23] dark:border-[#2A332C] dark:text-[#6BA882]">
                            {{ $isi }}
                        </div>
                    @endif
                @endforeach
            </div>
            <div class="mt-6">
                <a href="{{ route('siswa.feedback.create') }}" class="btn-primary w-full sm:w-auto">Isi Feedback Hari Ini</a>
            </div>
        @else
            <p class="text-sm text-muted py-8 text-center">Menu belum tersedia hari ini.</p>
        @endif
    </div>
@endsection
