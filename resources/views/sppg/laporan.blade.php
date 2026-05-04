@extends('layouts.app')
@section('title', 'Laporan Feedback')
@section('page_title', 'Laporan Feedback')

@section('content')
    <div class="card-white">
        <form method="GET" class="grid sm:grid-cols-3 gap-3 mb-5">
            <div>
                <label class="label">Tanggal</label>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="input">
            </div>
            <div>
                <label class="label">Sekolah</label>
                <select name="sekolah" class="input">
                    <option value="">Semua sekolah</option>
                    @foreach (['SMANBA', 'NESABA', 'MANSAPAS'] as $s)
                        <option value="{{ $s }}" @selected(request('sekolah') === $s)>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
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
                            <th>Foto</th>
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
                                <td>
                                    @if ($f->foto)
                                        <img src="{{ asset('storage/' . $f->foto) }}" class="w-10 h-10 rounded-lg object-cover">
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
@endsection
