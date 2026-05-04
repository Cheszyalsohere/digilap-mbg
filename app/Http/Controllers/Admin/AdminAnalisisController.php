<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Sppg;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminAnalisisController extends Controller
{
    public function index(Request $request): View
    {
        $bulan = $request->input('bulan', now()->format('Y-m'));
        $start = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();
        $end   = $start->copy()->endOfMonth();

        $rows = Sppg::all()->map(function ($sppg) use ($start, $end) {
            $feedbacks = Feedback::whereHas('menu', fn ($q) => $q->where('sppg_id', $sppg->id))
                ->whereBetween('created_at', [$start, $end])
                ->get();
            $count = $feedbacks->count();
            $avg = $count ? round($feedbacks->avg('rating'), 2) : null;
            $puas = $count ? round($feedbacks->where('rating', '>=', 3)->count() / $count * 100, 1) : null;
            $status = $puas === null ? 'kosong' : ($puas < 70 ? 'penyimpangan' : 'normal');
            return compact('sppg', 'count', 'avg', 'puas', 'status');
        });

        return view('admin.analisis', [
            'rows'   => $rows,
            'bulan'  => $bulan,
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $sppgId = $request->input('sppg_id');
        $bulan  = $request->input('bulan', now()->format('Y-m'));
        $start  = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();
        $end    = $start->copy()->endOfMonth();

        $query = Feedback::with(['user', 'menu.sppg'])
            ->whereBetween('created_at', [$start, $end]);

        if ($sppgId) {
            $query->whereHas('menu', fn ($q) => $q->where('sppg_id', $sppgId));
        }

        $feedbacks = $query->orderBy('created_at')->get();

        $filename = 'feedback-digilap-' . $bulan . '.csv';

        return response()->stream(function () use ($feedbacks) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Tanggal', 'Siswa', 'Sekolah', 'SPPG', 'Rating', 'Komentar']);
            foreach ($feedbacks as $f) {
                fputcsv($out, [
                    $f->created_at->format('Y-m-d H:i'),
                    $f->user?->name,
                    $f->user?->sekolah,
                    $f->menu?->sppg?->name,
                    $f->rating,
                    $f->komentar,
                ]);
            }
            fclose($out);
        }, Response::HTTP_OK, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }
}
