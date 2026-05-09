@extends('layouts.app')
@section('title', 'Riwayat Feedback')
@section('page_title', 'Riwayat Feedback')

@section('content')
    <div class="card-white">
        <h2 class="text-lg font-semibold mb-4">Riwayat Feedback Anda</h2>
        @if ($feedbacks->isEmpty())
            <p class="text-sm text-muted py-8 text-center">Belum ada feedback.</p>
        @else
            <div class="overflow-x-auto -mx-5 sm:mx-0 px-5 sm:px-0">
                <table class="table-base">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Foto</th>
                            <th>Rating</th>
                            <th>Komentar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($feedbacks as $f)
                            <tr>
                                <td class="whitespace-nowrap">{{ $f->created_at->translatedFormat('d M Y H:i') }}</td>
                                <td>
                                    @if ($f->foto)
                                        <img src="{{ asset('storage/' . $f->foto) }}" class="w-10 h-10 rounded-lg object-cover">
                                    @else
                                        <span class="text-xs text-muted">—</span>
                                    @endif
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
