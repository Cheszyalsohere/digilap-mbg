@extends('layouts.app')
@section('title', 'Analisis Penyimpangan')
@section('page_title', 'Analisis Penyimpangan')

@push('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@section('content')
    @php
        $bulanLabel = \Carbon\Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('F Y');
    @endphp

    <div class="print-header">
        <h1>DIGILAP MBG</h1>
        <p>Laporan Analisis Penyimpangan MBG</p>
        <p>Periode: {{ $bulanLabel }} | Dicetak: {{ now()->translatedFormat('d M Y H:i') }}</p>
    </div>

    <div class="card-white mb-6 no-print">
        <form method="GET" class="grid sm:grid-cols-[auto_1fr] gap-3 items-end">
            <div>
                <label class="label">Bulan</label>
                <input type="month" name="bulan" value="{{ $bulan }}" class="input sm:w-48">
            </div>
            <div class="flex flex-wrap gap-2">
                <button class="btn-primary flex-1 sm:flex-none">Tampilkan</button>
                <a href="{{ route('admin.export', ['bulan' => $bulan]) }}" class="btn-secondary flex-1 sm:flex-none">Export CSV</a>
                <a href="{{ route('admin.analisis.export-pdf', ['bulan' => $bulan]) }}"
                   target="_blank"
                   class="inline-flex items-center justify-center gap-2 px-5 py-2.5 min-h-[44px] rounded-xl bg-accent text-white font-medium hover:opacity-90 transition flex-1 sm:flex-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v8.586l2.293-2.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V4a1 1 0 011-1zm-7 13a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                    </svg>
                    Export PDF Analisis
                </a>
                <a href="{{ route('admin.laporan.export-pdf', ['bulan' => $bulan]) }}"
                   target="_blank"
                   class="inline-flex items-center justify-center gap-2 px-5 py-2.5 min-h-[44px] rounded-xl bg-accent text-white font-medium hover:opacity-90 transition flex-1 sm:flex-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v8.586l2.293-2.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V4a1 1 0 011-1zm-7 13a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                    </svg>
                    Export PDF Laporan
                </a>
                <button type="button" onclick="window.print()" class="btn-secondary no-print flex-1 sm:flex-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd"/>
                    </svg>
                    Print
                </button>
            </div>
        </form>
    </div>

    <div class="card-white mb-6">
        <h3 class="text-base font-semibold mb-3">Ringkasan per SPPG</h3>
        <div class="overflow-x-auto -mx-5 sm:mx-0 px-5 sm:px-0">
            <table class="table-base">
                <thead>
                    <tr>
                        <th>SPPG</th>
                        <th>Lokasi</th>
                        <th>Total Feedback</th>
                        <th>Rata-rata Rating</th>
                        <th>% Kepuasan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rows as $row)
                        <tr>
                            <td class="font-medium">{{ $row['sppg']->name }}</td>
                            <td>{{ $row['sppg']->lokasi }}</td>
                            <td>{{ $row['count'] }}</td>
                            <td>{{ $row['avg'] ?? '—' }}</td>
                            <td>{{ $row['puas'] !== null ? $row['puas'].'%' : '—' }}</td>
                            <td>
                                @if ($row['status'] === 'penyimpangan')
                                    <span class="badge-danger">⚠ Penyimpangan</span>
                                @elseif ($row['status'] === 'normal')
                                    <span class="badge-success">✓ Normal</span>
                                @else
                                    <span class="badge-info">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-white no-print">
        <h3 class="text-base font-semibold mb-3">Perbandingan % Kepuasan antar SPPG</h3>
        <div class="chart-wrap">
            <canvas id="puasChart"></canvas>
        </div>
    </div>

    <div class="print-footer">
        DIGILAP MBG — OPSI 2026 | Dicetak pada {{ now()->translatedFormat('d M Y H:i') }}
    </div>

    <script>
        const data = @json($rows->values());
        new Chart(document.getElementById('puasChart'), {
            type: 'bar',
            data: {
                labels: data.map(d => d.sppg.name),
                datasets: [{
                    label: '% Kepuasan',
                    data: data.map(d => d.puas ?? 0),
                    backgroundColor: data.map(d => (d.puas ?? 0) < 70 ? '#C0392B' : '#5C8B6E'),
                    borderRadius: 8,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { min: 0, max: 100, ticks: { callback: v => v + '%' } } },
                plugins: { legend: { display: false } }
            }
        });
    </script>
@endsection
