@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('page_title', 'Dashboard Admin')

@push('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@section('content')
    @if ($hasPenyimpangan)
        <div class="mb-5 px-4 py-3 rounded-xl bg-[#FADADD] dark:bg-[#3D1A1F] text-[#922B21] dark:text-[#F0A0A0] text-sm font-medium flex items-center gap-2">
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

    <div class="card-white mb-6">
        <h3 class="text-base font-semibold mb-3">Rata-rata Rating per SPPG (4 Minggu Terakhir)</h3>
        <div class="chart-wrap">
            <canvas id="weeklyChart"></canvas>
        </div>
    </div>

    <div class="card-white mb-6">
        <h3 class="text-base font-semibold mb-3">Ringkasan Bulanan per SPPG</h3>
        <div class="overflow-x-auto -mx-5 sm:mx-0 px-5 sm:px-0">
            <table class="table-base">
                <thead>
                    <tr>
                        <th>SPPG / Lokasi</th>
                        <th class="text-center">Total Feedback</th>
                        <th>Rata-rata</th>
                        <th class="text-center">% Kepuasan</th>
                        <th class="text-center">Trend vs Bulan Lalu</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sppgs as $row)
                        <tr>
                            <td>
                                <div class="font-medium">{{ $row['sppg']->name }}</div>
                                <div class="text-xs text-muted">{{ $row['sppg']->lokasi }}</div>
                            </td>
                            <td class="text-center">{{ $row['count'] }}</td>
                            <td>
                                @if ($row['avg'] !== null)
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold">{{ $row['avg'] }}</span>
                                        <span class="text-accent text-sm" aria-hidden="true">
                                            @php
                                                $full = (int) floor($row['avg']);
                                                $half = ($row['avg'] - $full) >= 0.5;
                                            @endphp
                                            @for ($i = 0; $i < 5; $i++)
                                                @if ($i < $full)
                                                    ★
                                                @elseif ($i === $full && $half)
                                                    ☆
                                                @else
                                                    <span class="text-bordered dark:text-[#2A332C]">★</span>
                                                @endif
                                            @endfor
                                        </span>
                                    </div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $row['puas'] !== null ? $row['puas'].'%' : '—' }}</td>
                            <td class="text-center">
                                @if ($row['trend'] === 'naik')
                                    <span class="inline-flex items-center gap-1 text-[#1E7A45] dark:text-[#8FE4B0] font-semibold text-xs">↑ Naik</span>
                                @elseif ($row['trend'] === 'turun')
                                    <span class="inline-flex items-center gap-1 text-danger font-semibold text-xs">↓ Turun</span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-muted font-semibold text-xs">→ Sama</span>
                                @endif
                            </td>
                            <td class="text-center">
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

    <div class="grid lg:grid-cols-2 gap-4 sm:gap-5 mb-6">
        <div class="card-white">
            <h3 class="text-base font-semibold mb-3">Statistik Keseluruhan</h3>
            <dl class="space-y-3">
                <div class="flex items-center justify-between">
                    <dt class="text-sm text-muted">Total feedback sejak awal</dt>
                    <dd class="text-sm font-semibold">{{ number_format($totalAllTime) }}</dd>
                </div>
                <div class="flex items-center justify-between">
                    <dt class="text-sm text-muted">Rata-rata rating keseluruhan</dt>
                    <dd class="text-sm font-semibold">{{ $avgAllTime ?? '—' }}{{ $avgAllTime ? ' / 5' : '' }}</dd>
                </div>
                <div class="flex items-start justify-between gap-3">
                    <dt class="text-sm text-muted">Performa terbaik</dt>
                    <dd class="text-sm font-semibold text-right">
                        @if ($bestSppg)
                            <span class="text-[#1E7A45] dark:text-[#8FE4B0]">{{ $bestSppg['sppg']->name }}</span>
                            <span class="text-xs text-muted block">{{ $bestSppg['avg'] }} / 5</span>
                        @else — @endif
                    </dd>
                </div>
                <div class="flex items-start justify-between gap-3">
                    <dt class="text-sm text-muted">Performa terburuk</dt>
                    <dd class="text-sm font-semibold text-right">
                        @if ($worstSppg)
                            <span class="text-danger">{{ $worstSppg['sppg']->name }}</span>
                            <span class="text-xs text-muted block">{{ $worstSppg['avg'] }} / 5</span>
                        @else — @endif
                    </dd>
                </div>
                <div class="flex items-start justify-between gap-3">
                    <dt class="text-sm text-muted">Hari dengan feedback terbanyak</dt>
                    <dd class="text-sm font-semibold text-right">
                        @if ($busiestDay)
                            {{ $busiestDay['date']->translatedFormat('d M Y') }}
                            <span class="text-xs text-muted block">{{ $busiestDay['count'] }} feedback</span>
                        @else — @endif
                    </dd>
                </div>
            </dl>
        </div>

        <div class="card-white">
            <h3 class="text-base font-semibold mb-3">Aktivitas Terkini</h3>

            <div class="mb-4">
                <p class="text-xs uppercase text-muted font-semibold tracking-wide mb-2">Feedback Terbaru</p>
                @forelse ($recentFeedbacks as $f)
                    <div class="flex items-start justify-between gap-3 py-2 border-b border-bordered dark:border-[#2A332C] last:border-b-0">
                        <div class="min-w-0">
                            <p class="text-sm font-medium truncate">{{ $f->user?->name ?? '—' }}</p>
                            <p class="text-xs text-muted truncate">{{ $f->menu?->sppg?->name ?? '—' }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-sm font-semibold text-accent">{{ $f->rating }}/5</p>
                            <p class="text-[11px] text-muted">{{ $f->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-muted py-2">Belum ada feedback.</p>
                @endforelse
            </div>

            <div>
                <p class="text-xs uppercase text-muted font-semibold tracking-wide mb-2">Log Aktivitas Terbaru</p>
                @forelse ($recentLogs as $log)
                    <div class="flex items-start justify-between gap-3 py-2 border-b border-bordered dark:border-[#2A332C] last:border-b-0">
                        <div class="min-w-0">
                            <p class="text-sm truncate">{{ $log->action }}</p>
                            <p class="text-xs text-muted truncate">{{ $log->user?->name ?? 'Sistem' }}</p>
                        </div>
                        <p class="text-[11px] text-muted shrink-0">{{ $log->created_at->diffForHumans() }}</p>
                    </div>
                @empty
                    <p class="text-xs text-muted py-2">Belum ada aktivitas.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="card-white">
        <h3 class="text-base font-semibold mb-3">Jumlah Feedback Harian per SPPG (30 Hari Terakhir)</h3>
        <div class="chart-wrap">
            <canvas id="dailyChart"></canvas>
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

        const dailyLabels = @json($dailyChartLabels);
        const dailyDatasets = @json($dailyChartData).map((d, i) => ({
            label: d.name,
            data: d.data,
            borderColor: ['#5C8B6E', '#D4A853', '#7A7670', '#4A7259', '#C0392B'][i % 5],
            backgroundColor: 'transparent',
            tension: 0.3,
            pointRadius: 2,
        }));
        new Chart(document.getElementById('dailyChart'), {
            type: 'line',
            data: { labels: dailyLabels, datasets: dailyDatasets },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
                plugins: { legend: { position: 'bottom' } }
            }
        });
    </script>
@endsection
