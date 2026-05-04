<?php

namespace App\Http\Controllers\Sppg;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SppgLaporanController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = Feedback::with(['user', 'menu'])
            ->whereHas('menu', fn ($q) => $q->where('sppg_id', $user->sppg_id))
            ->latest();

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->date('tanggal'));
        }

        if ($request->filled('sekolah')) {
            $query->whereHas('user', fn ($q) => $q->where('sekolah', $request->string('sekolah')));
        }

        $feedbacks = $query->paginate(20)->withQueryString();

        return view('sppg.laporan', compact('feedbacks'));
    }
}
