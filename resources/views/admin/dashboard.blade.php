@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('page_title', 'Dashboard Admin')

@push('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@section('content')
    @if ($hasPenyimpangan)
        <div class="mb-5 px-4 py-3 rounded-xl bg-[#FADADD] text-[#922B21] text-sm font-medium flex items-center gap-2">
            <span>⚠ Indikasi Penyimpangan Terdeteksi</span>
            <span class="text-xs font-normal opacity-80">— Ada SPPG dengan tingkat kepuasan di bawah 70%.</span>
        </div>
    @endif

    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-5 mb-6">
        <div class="card">
            <p class="text-xs text-muted uppercase">Total Siswa</p>
            <p class="text-2xl sm:text-3xl font-bold mt-1">{{ number_format($totalSiswa) }}</p>
        </div>
        <div class="card">
            <p class="text-xs text-muted uppercase">Total SPPG</p>
            <p class="text-2xl sm:text-3xl font-bold mt-1">{{ number_format($totalSppg) }}</p>
        </div>
        <div class="card col-span-2 sm:col-span-1">
            <p class="text-xs text-muted uppercase">Feedback Bulan Ini</p>
            <p class="text-2xl sm:text-3xl font-bold mt-1">{{ number_format($totalFeedback) }}</p>
        </div>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 mb-6">
        @foreach ($sppgs as $row)
            <div class="card">
                <div class="flex items-start justify-between mb-2">
                    <div>
                        <p class="text-sm font-semibold">{{ $row['sppg']->name }}</p>
                        <p class="text-xs text-muted">{{ $row['sppg']->lokasi }}</p>
                    </div>
                    @if ($row['status'] === 'penyimpangan')
                        <span class="badge-danger">⚠ Penyimpangan</span>
                    @elseif ($row['status'] === 'normal')
                        <span class="badge-success">✓ Normal</span>
                    @else
                        <span class="badge-info">Belum ada data</span>
                    @endif
                </div>
                <div class="flex items-baseline gap-2">
                    <p class="text-2xl font-bold">{{ $row['avg'] ?? '—' }}</p>
                    <p class="text-xs text-muted">/5 rata-rata bulan ini</p>
                </div>
                <p class="text-xs text-muted mt-1">Kepuasan: {{ $row['puas'] !== null ? $row['puas'].'%' : '—' }} &middot; {{ $row['count'] }} feedback</p>
            </div>
        @endforeach
    </div>

    <div class="card-white">
        <h3 class="text-base font-semibold mb-3">Rata-rata Rating per SPPG (4 Minggu Terakhir)</h3>
        <div class="chart-wrap">
            <canvas id="weeklyChart"></canvas>
        </div>
    </div>

    <script>
        const labels = @json($chartLabels);
        const datasets = @json($chartData).map((d, i) => ({
            label: d.name,
            data: d.data,
            borderColor: ['#5C8B6E', '#D4A853', '#7A7670'][i % 3],
            backgroundColor: ['#5C8B6E22', '#D4A85322', '#7A767022'][i % 3],
            tension: 0.35,
            fill: true,
        }));
        new Chart(document.getElementById('weeklyChart'), {
            type: 'line',
            data: { labels, datasets },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { min: 0, max: 5, ticks: { stepSize: 1 } } },
                plugins: { legend: { position: 'bottom' } }
            }
        });
    </script>
@endsection
