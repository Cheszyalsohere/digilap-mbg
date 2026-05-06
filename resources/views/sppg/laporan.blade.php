@extends('layouts.app')
@section('title', 'Laporan Feedback')
@section('page_title', 'Laporan Feedback')

@section('content')
    <div class="card-white">
        <p class="text-xs text-muted mb-4">
            Menampilkan laporan dari siswa
            <span class="font-semibold text-ink">{{ $sekolah ?? '—' }}</span>
            &mdash;
            <span class="font-semibold text-ink">{{ auth()->user()->sppg?->name ?? 'SPPG' }}</span>.
        </p>

        <form method="GET" class="grid sm:grid-cols-3 gap-3 mb-5">
            <div>
                <label class="label">Tanggal</label>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="input">
            </div>
            <div class="flex items-end gap-2 sm:col-start-3">
                <button class="btn-primary">Filter</button>
                <a href="{{ route('sppg.laporan') }}" class="btn-secondary">Reset</a>
            </div>
        </form>

        @if ($feedbacks->isEmpty())
            <p class="text-sm text-muted py-8 text-center">Belum ada feedback.</p>
        @else
            <div class="overflow-x-auto">
                <table class="table-base">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Siswa</th>
                            <th>Sekolah</th>
                            <th>Menu</th>
                            <th>Foto Menu</th>
                            <th>Foto Siswa</th>
                            <th>Rating</th>
                            <th>Komentar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($feedbacks as $f)
                            <tr>
                                <td class="whitespace-nowrap">{{ $f->created_at->translatedFormat('d M Y H:i') }}</td>
                                <td class="whitespace-nowrap">{{ $f->user?->name }}</td>
                                <td>{{ $f->user?->sekolah }}</td>
                                <td class="whitespace-nowrap">
                                    @if ($f->menu)
                                        <span class="text-xs text-muted">{{ $f->menu->tanggal->translatedFormat('d M Y') }}</span>
                                        @if ($f->menu->tanggal->isToday())
                                            <a href="{{ route('sppg.menu.edit', $f->menu) }}"
                                               class="ml-1 inline-flex items-center text-primary hover:text-primary-dark"
                                               title="Edit menu">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828zM2 17a1 1 0 011-1h14a1 1 0 110 2H3a1 1 0 01-1-1z"/>
                                                </svg>
                                            </a>
                                        @endif
                                    @else <span class="text-xs text-muted">—</span> @endif
                                </td>
                                <td>
                                    @if ($f->menu?->foto_menu)
                                        <button type="button" class="lightbox-trigger block"
                                                data-src="{{ asset('storage/' . $f->menu->foto_menu) }}"
                                                title="Lihat foto menu">
                                            <img src="{{ asset('storage/' . $f->menu->foto_menu) }}"
                                                 class="w-[60px] h-[60px] rounded-lg object-cover hover:opacity-80 transition">
                                        </button>
                                    @else <span class="text-xs text-muted">—</span> @endif
                                </td>
                                <td>
                                    @if ($f->foto)
                                        <button type="button" class="lightbox-trigger block"
                                                data-src="{{ asset('storage/' . $f->foto) }}">
                                            <img src="{{ asset('storage/' . $f->foto) }}"
                                                 class="w-10 h-10 rounded-lg object-cover hover:opacity-80 transition">
                                        </button>
                                    @else <span class="text-xs text-muted">—</span> @endif
                                </td>
                                <td class="text-accent text-sm tracking-tighter whitespace-nowrap">
                                    {!! str_repeat('★', $f->rating) . str_repeat('☆', 5 - $f->rating) !!}
                                </td>
                                <td>{{ $f->komentar ?: '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $feedbacks->links() }}</div>
        @endif
    </div>

    <div id="lightbox" class="hidden fixed inset-0 z-50 bg-black/80 items-center justify-center p-4 cursor-zoom-out">
        <img id="lightbox-img" src="" alt="" class="max-w-full max-h-[90vh] rounded-2xl shadow-2xl">
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            const lightbox = document.getElementById('lightbox');
            const img = document.getElementById('lightbox-img');
            if (!lightbox) return;

            document.querySelectorAll('.lightbox-trigger').forEach(btn => {
                btn.addEventListener('click', () => {
                    img.src = btn.dataset.src;
                    lightbox.classList.remove('hidden');
                    lightbox.classList.add('flex');
                });
            });
            const close = () => {
                lightbox.classList.add('hidden');
                lightbox.classList.remove('flex');
                img.src = '';
            };
            lightbox.addEventListener('click', close);
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') close();
            });
        })();
    </script>
@endpush
