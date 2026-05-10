<?php

namespace App\Http\Controllers;

use App\Concerns\LogsActivity;
use App\Models\Allergy;
use App\Models\InvitationCode;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    use LogsActivity;

    public function show(): View
    {
        $allergies = Allergy::orderBy('id')->get();
        return view('auth.register', compact('allergies'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'              => ['required', 'string', 'max:120'],
            'password'          => ['required', 'string', 'min:8', 'confirmed'],
            'code'              => ['required', 'string', 'max:32'],
            'allergies'         => ['nullable', 'array'],
            'allergies.*'       => ['integer', 'exists:allergies,id'],
            'allergy_lainnya'   => ['nullable', 'string', 'max:255'],
        ], [], [
            'name'     => 'nama lengkap',
            'password' => 'password',
            'code'     => 'kode undangan',
        ]);

        $code = InvitationCode::where('code', strtoupper(trim($data['code'])))->first();

        if (! $code || ! $code->isUsable()) {
            return back()
                ->withErrors(['code' => 'Kode undangan tidak valid atau sudah tidak aktif.'])
                ->withInput();
        }

        $username = User::generateUsername($data['name'], $code->sekolah);

        $user = User::create([
            'name'     => $data['name'],
            'username' => $username,
            'password' => Hash::make($data['password']),
            'role'     => 'siswa',
            'sekolah'  => $code->sekolah,
            'sppg_id'  => $code->sppg_id,
        ]);

        $code->increment('used_count');

        if (! empty($data['allergies'])) {
            $lainnyaId = Allergy::where('slug', 'lainnya')->value('id');
            $sync = [];
            foreach ($data['allergies'] as $allergyId) {
                $sync[$allergyId] = [
                    'catatan' => ($allergyId == $lainnyaId)
                        ? ($data['allergy_lainnya'] ?? null)
                        : null,
                ];
            }
            $user->allergies()->sync($sync);
        }

        Auth::login($user);
        $request->session()->regenerate();

        $this->logActivity(
            "Registrasi akun baru via kode {$code->code}",
            "Username: {$user->username}, Sekolah: {$user->sekolah}",
            $user
        );

        return redirect()->route('siswa.dashboard')->with(
            'success',
            "Akun berhasil dibuat! Username kamu adalah: {$username}. Simpan username ini untuk login berikutnya."
        );
    }
}
