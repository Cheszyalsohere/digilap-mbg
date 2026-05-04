<?php

namespace App\Http\Controllers\Sppg;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SppgDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $today = today();

        $menuToday = Menu::where('sppg_id', $user->sppg_id)
            ->whereDate('tanggal', $today)
            ->first();

        $todayFeedbacks = $menuToday
            ? Feedback::where('menu_id', $menuToday->id)->get()
            : collect();

        $totalToday = $todayFeedbacks->count();
        $avgRatingToday = $totalToday ? round($todayFeedbacks->avg('rating'), 2) : null;

        $latestComments = Feedback::with(['user', 'menu'])
            ->whereHas('menu', fn ($q) => $q->where('sppg_id', $user->sppg_id))
            ->whereNotNull('komentar')
            ->latest()
            ->take(8)
            ->get();

        $sudahInputMenu = (bool) $menuToday;

        return view('sppg.dashboard', compact(
            'totalToday', 'avgRatingToday', 'latestComments', 'sudahInputMenu', 'menuToday'
        ));
    }
}
