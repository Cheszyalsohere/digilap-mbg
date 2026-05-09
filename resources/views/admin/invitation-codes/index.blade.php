@extends('layouts.app')
@section('title', 'Kode Undangan')
@section('page_title', 'Kode Undangan')

@section('content')
    <div class="card-white">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <p class="text-sm text-muted">
                Kelola kode undangan untuk pendaftaran siswa baru.
                Total: <span class="font-semibold text-ink">{{ $codes->total() }}</span> kode.
            </p>
            <a href="{{ route('admin.invitation-codes.create') }}" class="btn-primary w-full sm:w-auto">+ Generate Kode Baru</a>
        </div>

        @if ($codes->isEmpty())
            <p class="text-sm text-muted py-10 text-center">Belum ada kode undangan. Klik "Generate Kode Baru" untuk membuat.</p>
        @else
            <div class="overflow-x-auto -mx-5 sm:mx-0 px-5 sm:px-0">
                <table class="table-base">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Sekolah</th>
                            <th>SPPG</th>
                            <th>Status</th>
                            <th>Expired</th>
                            <th>Pemakai</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($codes as $c)
                            <tr>
                                <td><code class="text-sm font-semibold text-primary-dark">{{ $c->code }}</code></td>
                                <td>{{ $c->sekolah }}</td>
                                <td>{{ $c->sppg?->name ?? '—' }}</td>
                                <td>
                                    @if ($c->is_active && (! $c->expires_at || ! $c->expires_at->isPast()))
                                        <span class="badge-success">Aktif</span>
                                    @elseif (! $c->is_active)
                                        <span class="px-2 py-0.5 rounded-full bg-surface text-muted text-[11px] font-semibold">Nonaktif</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full bg-[#FADADD] text-[#922B21] text-[11px] font-semibold">Expired</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap text-xs text-muted">
                                    {{ $c->expires_at ? $c->expires_at->translatedFormat('d M Y, H:i') : 'Tidak ada' }}
                                </td>
                                <td class="text-center">{{ $c->used_count }}</td>
                                <td class="text-right whitespace-nowrap">
                                    <form action="{{ route('admin.invitation-codes.update', $c) }}" method="POST" class="inline">
                                        @csrf @method('PUT')
                                        <button class="text-primary text-sm hover:underline">
                                            {{ $c->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.invitation-codes.destroy', $c) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Hapus kode {{ $c->code }}?')">
                                        @csrf @method('DELETE')
                                        <button class="text-danger text-sm hover:underline ml-2">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $codes->links() }}</div>
        @endif
    </div>
@endsection
