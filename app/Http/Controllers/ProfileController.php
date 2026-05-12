<?php

namespace App\Http\Controllers;

use App\Models\Allergy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();
        $allergies = $user->isSiswa() ? Allergy::orderBy('id')->get() : collect();
        $userAllergyIds = $user->isSiswa() ? $user->allergies()->pluck('allergies.id')->toArray() : [];

        $allergyLainnya = $user->isSiswa()
            ? $user->allergies()->where('slug', 'lainnya')->first()
            : null;
        $userAllergyLainnya = $allergyLainnya
            ? optional($allergyLainnya->pivot)->catatan
            : null;

        return view('profile.edit', compact('user', 'allergies', 'userAllergyIds', 'userAllergyLainnya'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $rules = [
            'name'     => ['required', 'string', 'max:120'],
            'email'    => ['nullable', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ];

        if ($user->isSiswa()) {
            $rules['username'] = [
                'required', 'string', 'min:4', 'max:30',
                'regex:/^[a-z0-9_]+$/',
                Rule::unique('users', 'username')->ignore($user->id),
            ];
            $rules['allergies']        = ['nullable', 'array'];
            $rules['allergies.*']      = ['integer', 'exists:allergies,id'];
            $rules['allergy_lainnya']  = ['nullable', 'string', 'max:255'];
        }

        $data = $request->validate($rules, [
            'username.unique' => 'Username sudah digunakan, coba yang lain.',
            'username.regex'  => 'Username hanya boleh huruf kecil, angka, dan underscore.',
            'username.min'    => 'Username minimal 4 karakter.',
            'username.max'    => 'Username maksimal 30 karakter.',
        ]);

        $usernameChanged = false;
        if ($user->isSiswa() && $data['username'] !== $user->username) {
            $user->username = $data['username'];
            $usernameChanged = true;
        }

        $user->name  = $data['name'];
        $user->email = $data['email'] ?? null;
        if (! empty($data['password'])) {
            $user->password = $data['password'];
        }
        $user->save();

        if ($user->isSiswa()) {
            $sync = [];
            $lainnyaId = Allergy::where('slug', 'lainnya')->value('id');
            foreach ($data['allergies'] ?? [] as $allergyId) {
                $sync[$allergyId] = [
                    'catatan' => ($allergyId == $lainnyaId)
                        ? ($data['allergy_lainnya'] ?? null)
                        : null,
                ];
            }
            $user->allergies()->sync($sync);
        }

        $message = $usernameChanged
            ? "Username berhasil diubah menjadi: {$user->username}"
            : 'Profil berhasil diperbarui.';

        return back()->with('success', $message);
    }
}
