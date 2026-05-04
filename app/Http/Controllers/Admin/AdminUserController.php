<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sppg;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminUserController extends Controller
{
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
        if (! empty($data['password'])) {
            $user->password = $data['password'];
        }
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User dihapus.');
    }

    public function show(User $user): RedirectResponse
    {
        return redirect()->route('admin.users.edit', $user);
    }
}
