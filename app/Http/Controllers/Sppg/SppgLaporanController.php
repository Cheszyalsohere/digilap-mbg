<?php

namespace App\Http\Controllers\Sppg;

use App\Http\Controllers\Controller;
use App\Models\Allergy;
use App\Models\Feedback;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SppgLaporanController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = Feedback::with(['user', 'menu'])
            ->whereHas('user', fn ($q) => $q->where('sppg_id', $user->sppg_id))
            ->latest();

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->date('tanggal'));
        }

        $feedbacks = $query->paginate(20)->withQueryString();

        $sekolah = User::where('sppg_id', $user->sppg_id)
            ->where('role', 'siswa')
            ->value('sekolah');

        $allergyStats = Allergy::leftJoin('user_allergies', 'allergies.id', '=', 'user_allergies.allergy_id')
            ->leftJoin('users', function ($j) use ($user) {
                $j->on('users.id', '=', 'user_allergies.user_id')
                    ->where('users.role', 'siswa')
                    ->where('users.sppg_id', $user->sppg_id);
            })
            ->groupBy('allergies.id', 'allergies.name', 'allergies.slug')
            ->orderBy('allergies.id')
            ->select('allergies.id', 'allergies.name', 'allergies.slug', DB::raw('COUNT(users.id) as siswa_count'))
            ->get()
            ->filter(fn ($r) => $r->siswa_count > 0)
            ->values();

        return view('sppg.laporan', compact('feedbacks', 'sekolah', 'allergyStats'));
    }

    public function exportPdf(Request $request)
    {
        $user = $request->user();
        $bulan = $request->input('bulan', now()->format('Y-m'));
        $start = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();
        $end   = $start->copy()->endOfMonth();

        $feedbacks = Feedback::with(['user', 'menu'])
            ->whereHas('user', fn ($q) => $q->where('sppg_id', $user->sppg_id))
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at')
            ->get();

        $sekolah = User::where('sppg_id', $user->sppg_id)
            ->where('role', 'siswa')
            ->value('sekolah');

        $count = $feedbacks->count();
        $avg = $count ? round($feedbacks->avg('rating'), 2) : null;
        $puas = $count ? round($feedbacks->where('rating', '>=', 3)->count() / $count * 100, 1) : null;

        return Pdf::loadView('pdf.laporan', [
            'feedbacks'    => $feedbacks,
            'sppgName'     => $user->sppg?->name ?? 'SPPG',
            'sekolah'      => $sekolah ?: '—',
            'periode'      => $start->translatedFormat('F Y'),
            'count'        => $count,
            'avg'          => $avg,
            'puas'         => $puas,
            'tanggalCetak' => now()->translatedFormat('d F Y, H:i'),
        ])->setPaper('a4', 'portrait')->stream('laporan-feedback-' . $bulan . '.pdf');
    }
}
