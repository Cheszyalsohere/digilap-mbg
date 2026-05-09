<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvitationCode;
use App\Models\Sppg;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminInvitationCodeController extends Controller
{
    private const SEKOLAH_TO_SPPG = [
        'SMANBA'   => 'SPPG Kalirejo',
        'NESABA'   => 'SPPG Rembang',
        'MANSAPAS' => 'SPPG Bangil',
    ];

    public function index(): View
    {
        $codes = InvitationCode::with('sppg')->latest()->paginate(20);
        return view('admin.invitation-codes.index', compact('codes'));
    }

    public function create(): View
    {
        $sppgs = Sppg::orderBy('name')->get();
        return view('admin.invitation-codes.create', compact('sppgs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'sekolah'    => ['required', Rule::in(['SMANBA', 'NESABA', 'MANSAPAS'])],
            'expires_at' => ['nullable', 'date', 'after:now'],
        ]);

        $sppgName = self::SEKOLAH_TO_SPPG[$data['sekolah']];
        $sppg = Sppg::where('name', $sppgName)->first();

        if (! $sppg) {
            return back()->withErrors(['sekolah' => "SPPG untuk {$data['sekolah']} tidak ditemukan."])->withInput();
        }

        $code = InvitationCode::create([
            'code'       => InvitationCode::generateUniqueCode($data['sekolah']),
            'sekolah'    => $data['sekolah'],
            'sppg_id'    => $sppg->id,
            'is_active'  => true,
            'expires_at' => $data['expires_at'] ?? null,
        ]);

        return redirect()->route('admin.invitation-codes.index')
            ->with('success', "Kode undangan dibuat: {$code->code}");
    }

    public function update(Request $request, InvitationCode $invitation_code): RedirectResponse
    {
        $invitation_code->update(['is_active' => ! $invitation_code->is_active]);

        $status = $invitation_code->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Kode {$invitation_code->code} {$status}.");
    }

    public function destroy(InvitationCode $invitation_code): RedirectResponse
    {
        $code = $invitation_code->code;
        $invitation_code->delete();
        return back()->with('success', "Kode {$code} dihapus.");
    }
}
