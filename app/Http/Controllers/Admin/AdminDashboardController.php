<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Feedback;
use App\Models\Sppg;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $totalSiswa    = User::where('role', 'siswa')->count();
        $totalSppg     = Sppg::count();
        $startOfMonth  = now()->startOfMonth();
        $startOfPrevMonth = now()->subMonthNoOverflow()->startOfMonth();
        $endOfPrevMonth   = now()->subMonthNoOverflow()->endOfMonth();
        $totalFeedback = Feedback::where('created_at', '>=', $startOfMonth)->count();

        $sppgs = Sppg::all()->map(function ($sppg) use ($startOfMonth, $startOfPrevMonth, $endOfPrevMonth) {
            $current = Feedback::whereHas('menu', fn ($q) => $q->where('sppg_id', $sppg->id))
                ->where('created_at', '>=', $startOfMonth)
                ->get();
            $count = $current->count();
            $avg = $count ? round($current->avg('rating'), 2) : null;
            $puas = $count ? round($current->where('rating', '>=', 3)->count() / $count * 100, 1) : null;
            $status = $puas === null ? 'kosong' : ($puas < 70 ? 'penyimpangan' : 'normal');

            $previous = Feedback::whereHas('menu', fn ($q) => $q->where('sppg_id', $sppg->id))
                ->whereBetween('created_at', [$startOfPrevMonth, $endOfPrevMonth])
                ->get();
            $prevAvg = $previous->count() ? round($previous->avg('rating'), 2) : null;

            $trend = 'sama';
            if ($avg !== null && $prevAvg !== null) {
                if ($avg > $prevAvg + 0.05) $trend = 'naik';
                elseif ($avg < $prevAvg - 0.05) $trend = 'turun';
            } elseif ($avg !== null && $prevAvg === null) {
                $trend = 'naik';
            }

            return [
                'sppg'    => $sppg,
                'count'   => $count,
                'avg'     => $avg,
                'prevAvg' => $prevAvg,
                'puas'    => $puas,
                'status'  => $status,
                'trend'   => $trend,
            ];
        });

        $hasPenyimpangan = $sppgs->where('status', 'penyimpangan')->isNotEmpty();

        $weeks = collect(range(3, 0))->map(function ($i) {
            $start = now()->startOfWeek()->subWeeks($i);
            return [
                'label' => 'M' . (4 - $i),
                'start' => $start,
                'end'   => $start->copy()->endOfWeek(),
            ];
        });

        $chartLabels = $weeks->pluck('label');
        $chartData = Sppg::all()->map(function ($sppg) use ($weeks) {
            return [
                'name' => $sppg->name,
                'data' => $weeks->map(function ($w) use ($sppg) {
                    $avg = Feedback::whereHas('menu', fn ($q) => $q->where('sppg_id', $sppg->id))
                        ->whereBetween('created_at', [$w['start'], $w['end']])
                        ->avg('rating');
                    return $avg ? round($avg, 2) : 0;
                })->values(),
            ];
        });

        $totalAllTime  = Feedback::count();
        $avgAllTime    = $totalAllTime ? round(Feedback::avg('rating'), 2) : null;

        $sppgRanked = Sppg::all()->map(function ($sppg) {
            $avg = Feedback::whereHas('menu', fn ($q) => $q->where('sppg_id', $sppg->id))->avg('rating');
            return [
                'sppg' => $sppg,
                'avg'  => $avg ? round($avg, 2) : null,
            ];
        })->filter(fn ($r) => $r['avg'] !== null);

        $bestSppg  = $sppgRanked->sortByDesc('avg')->first();
        $worstSppg = $sppgRanked->sortBy('avg')->first();

        $busiestRow = Feedback::selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->groupBy('d')
            ->orderByDesc('c')
            ->first();
        $busiestDay = $busiestRow ? [
            'date'  => Carbon::parse($busiestRow->d),
            'count' => (int) $busiestRow->c,
        ] : null;

        $recentFeedbacks = Feedback::with(['user', 'menu.sppg'])
            ->latest()
            ->take(5)
            ->get();

        $recentLogs = ActivityLog::with('user')
            ->latest()
            ->take(5)
            ->get();

        $startDaily = now()->subDays(29)->startOfDay();
        $endDaily   = now()->endOfDay();

        $dailyLabels = collect(range(0, 29))->map(fn ($i) => $startDaily->copy()->addDays($i));
        $dailyChartData = Sppg::all()->map(function ($sppg) use ($dailyLabels, $startDaily, $endDaily) {
            $rows = Feedback::selectRaw('DATE(created_at) as d, COUNT(*) as c')
                ->whereHas('menu', fn ($q) => $q->where('sppg_id', $sppg->id))
                ->whereBetween('created_at', [$startDaily, $endDaily])
                ->groupBy('d')
                ->pluck('c', 'd');

            return [
                'name' => $sppg->name,
                'data' => $dailyLabels->map(fn ($day) => (int) ($rows[$day->format('Y-m-d')] ?? 0))->values(),
            ];
        });
        $dailyChartLabels = $dailyLabels->map(fn ($d) => $d->format('d/m'));

        return view('admin.dashboard', compact(
            'totalSiswa', 'totalSppg', 'totalFeedback', 'sppgs',
            'hasPenyimpangan', 'chartLabels', 'chartData',
            'totalAllTime', 'avgAllTime', 'bestSppg', 'worstSppg', 'busiestDay',
            'recentFeedbacks', 'recentLogs',
            'dailyChartLabels', 'dailyChartData'
        ));
    }
}
