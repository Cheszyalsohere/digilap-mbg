@extends('layouts.app')
@section('title', 'Analisis Penyimpangan')
@section('page_title', 'Analisis Penyimpangan')

@push('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@section('content')
    <div class="card-white mb-6">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="label">Bulan</label>
                <input type="month" name="bulan" value="{{ $bulan }}" class="input">
            </div>
            <div class="flex gap-2">
                <button class="btn-primary">Tampilkan</button>
                <a href="{{ route('admin.export', ['bulan' => $bulan]) }}" class="btn-secondary">Export CSV</a>
            </div>
        </form>
    </div>

    <div class="card-white mb-6">
        <h3 class="text-base font-semibold mb-3">Ringkasan per SPPG</h3>
        <div class="overflow-x-auto">
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

    <div class="card-white">
        <h3 class="text-base font-semibold mb-3">Perbandingan % Kepuasan antar SPPG</h3>
        <canvas id="puasChart" height="100"></canvas>
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
                scales: { y: { min: 0, max: 100, ticks: { callback: v => v + '%' } } },
                plugins: { legend: { display: false } }
            }
        });
    </script>
@endsection
