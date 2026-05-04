<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        $totalFeedback = Feedback::where('created_at', '>=', $startOfMonth)->count();

        $sppgs = Sppg::all()->map(function ($sppg) use ($startOfMonth) {
            $feedbacks = Feedback::whereHas('menu', fn ($q) => $q->where('sppg_id', $sppg->id))
                ->where('created_at', '>=', $startOfMonth)
                ->get();
            $count = $feedbacks->count();
            $avg = $count ? round($feedbacks->avg('rating'), 2) : null;
            $puas = $count ? round($feedbacks->where('rating', '>=', 3)->count() / $count * 100, 1) : null;
            $status = $puas === null ? 'kosong' : ($puas < 70 ? 'penyimpangan' : 'normal');

            return [
                'sppg'        => $sppg,
                'count'       => $count,
                'avg'         => $avg,
                'puas'        => $puas,
                'status'      => $status,
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

        return view('admin.dashboard', compact(
            'totalSiswa', 'totalSppg', 'totalFeedback', 'sppgs',
            'hasPenyimpangan', 'chartLabels', 'chartData'
        ));
    }
}
