<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiswaDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $today = today();

        $menuToday = Menu::where('sppg_id', $user->sppg_id)
            ->whereDate('tanggal', $today)
            ->first();

        $sudahFeedback = $menuToday
            ? Feedback::where('user_id', $user->id)->where('menu_id', $menuToday->id)->exists()
            : false;

        $riwayat = Feedback::with('menu')
            ->where('user_id', $user->id)
            ->whereBetween('created_at', [now()->subDays(7), now()])
            ->latest()
            ->take(7)
            ->get();

        return view('siswa.dashboard', compact('menuToday', 'sudahFeedback', 'riwayat'));
    }
}
