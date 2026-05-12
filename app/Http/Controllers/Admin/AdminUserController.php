<?php

namespace App\Http\Controllers\Admin;

use App\Concerns\LogsActivity;
use App\Http\Controllers\Controller;
use App\Models\Sppg;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminUserController extends Controller
{
    use LogsActivity;

    public function index(Request $request): View
    {
        $query = User::with('sppg')->latest('id');

        if ($request->filled('role')) {
            $query->where('role', $request->string('role'));
        }
        if ($request->filled('q')) {
            $q = $request->string('q');
            $query->where(fn ($w) => $w
                ->where('name', 'like', "%$q%")
                ->orWhere('username', 'like', "%$q%")
                ->orWhere('email', 'like', "%$q%"));
        }

        $users = $query->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        $sppgs = Sppg::orderBy('name')->get();
        return view('admin.users.create', compact('sppgs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:120'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'role'     => ['required', Rule::in(['siswa', 'sppg', 'admin'])],
            'sekolah'  => ['nullable', Rule::in(['SMANBA', 'NESABA', 'MANSAPAS'])],
            'sppg_id'  => ['nullable', 'exists:sppgs,id'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if ($data['role'] === 'siswa' && empty($data['sekolah'])) {
            return back()->withErrors(['sekolah' => 'Sekolah wajib diisi untuk siswa.'])->withInput();
        }

        $user = new User();
        $user->name     = $data['name'];
        $user->email    = $data['email'];
        $user->role     = $data['role'];
        $user->sekolah  = $data['role'] === 'siswa' ? $data['sekolah'] : null;
        $user->sppg_id  = in_array($data['role'], ['siswa', 'sppg']) ? ($data['sppg_id'] ?? null) : null;
        $user->password = $data['password'];
        $user->username = User::generateUsername($user->name, $user->sekolah);
        $user->save();

        $this->logActivity(
            "Membuat akun {$user->role} — {$user->username}",
            "Nama: {$user->name}, Email: {$user->email}",
            $user
        );

        return redirect()->route('admin.users.index')
            ->with('success', "User dibuat. Username: {$user->username}");
    }

    public function edit(User $user): View
    {
        $sppgs = Sppg::orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'sppgs'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:120'],
            'email'   => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'sekolah' => ['nullable', Rule::in(['SMANBA', 'NESABA', 'MANSAPAS'])],
            'sppg_id' => ['nullable', 'exists:sppgs,id'],
            'password'=> ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        $user->name    = $data['name'];
        $user->email   = $data['email'];
        $user->sekolah = $user->isSiswa() ? $data['sekolah'] : null;
        $user->sppg_id = in_array($user->role, ['siswa', 'sppg']) ? ($data['sppg_id'] ?? null) : null;
        $passwordReset = ! empty($data['password']);
        if ($passwordReset) {
            $user->password = $data['password'];
        }
        $user->save();

        if ($passwordReset) {
            $this->logActivity("Mereset password {$user->username}", null, $user);
        }

        return redirect()->route('admin.users.index')->with('success', 'User diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }
        $username = $user->username;
        $user->delete();

        $this->logActivity("Menghapus akun {$username}");

        return redirect()->route('admin.users.index')->with('success', 'User dihapus.');
    }

    public function show(User $user): RedirectResponse
    {
        return redirect()->route('admin.users.edit', $user);
    }

    public function exportCsv(): StreamedResponse
    {
        $siswa = User::with(['sppg', 'allergies'])
            ->where('role', 'siswa')
            ->orderBy('name')
            ->get();

        $filename = 'data-siswa-digilap-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($siswa) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");

            fputcsv($file, [
                'No', 'Nama', 'Username', 'Email',
                'Sekolah', 'SPPG', 'Alergi',
                'Catatan Alergi', 'Tanggal Daftar',
            ]);

            foreach ($siswa as $i => $s) {
                $catatan = optional($s->allergies->where('slug', 'lainnya')->first())
                    ->pivot?->catatan;

                fputcsv($file, [
                    $i + 1,
                    $s->name,
                    $s->username,
                    $s->email ?? '-',
                    $s->sekolah ?? '-',
                    $s->sppg?->name ?? '-',
                    $s->allergies->pluck('name')->join(', ') ?: '-',
                    $catatan ?: '-',
                    $s->created_at?->format('d/m/Y') ?? '-',
                ]);
            }

            fclose($file);
        };

        $this->logActivity("Mengekspor data siswa ke CSV ({$siswa->count()} baris)");

        return response()->stream($callback, 200, $headers);
    }
}
