<?php

namespace App\Http\Controllers\Sppg;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        $existing = Menu::where('sppg_id', $request->user()->sppg_id)
            ->whereDate('tanggal', today())
            ->first();

        if ($existing) {
            return redirect()->route('sppg.dashboard')
                ->with('error', 'Menu untuk hari ini sudah diinput.');
        }

        return view('sppg.menu.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'slot_1'    => ['required', 'string', 'max:255'],
            'slot_2'    => ['required', 'string', 'max:255'],
            'slot_3'    => ['required', 'string', 'max:255'],
            'slot_4'    => ['required', 'string', 'max:255'],
            'slot_5'    => ['required', 'string', 'max:255'],
            'foto_menu' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $user = $request->user();
        $today = today();

        $exists = Menu::where('sppg_id', $user->sppg_id)
            ->whereDate('tanggal', $today)
            ->exists();

        if ($exists) {
            return redirect()->route('sppg.dashboard')
                ->with('error', 'Menu untuk hari ini sudah diinput.');
        }

        $fotoPath = null;
        if ($request->hasFile('foto_menu')) {
            $fotoPath = $this->storeFoto($request->file('foto_menu'), $user->sppg_id, $today->toDateString());
        }

        Menu::create([
            'sppg_id'   => $user->sppg_id,
            'tanggal'   => $today,
            'slot_1'    => $data['slot_1'],
            'slot_2'    => $data['slot_2'],
            'slot_3'    => $data['slot_3'],
            'slot_4'    => $data['slot_4'],
            'slot_5'    => $data['slot_5'],
            'foto_menu' => $fotoPath,
        ]);

        return redirect()->route('sppg.dashboard')
            ->with('success', 'Menu hari ini berhasil disimpan.');
    }

    public function edit(Request $request, Menu $menu): View
    {
        $this->authorizeEdit($request, $menu);

        return view('sppg.menu.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $this->authorizeEdit($request, $menu);

        $data = $request->validate([
            'slot_1'      => ['required', 'string', 'max:255'],
            'slot_2'      => ['required', 'string', 'max:255'],
            'slot_3'      => ['required', 'string', 'max:255'],
            'slot_4'      => ['required', 'string', 'max:255'],
            'slot_5'      => ['required', 'string', 'max:255'],
            'foto_menu'   => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'hapus_foto'  => ['nullable', 'boolean'],
        ]);

        $payload = [
            'slot_1' => $data['slot_1'],
            'slot_2' => $data['slot_2'],
            'slot_3' => $data['slot_3'],
            'slot_4' => $data['slot_4'],
            'slot_5' => $data['slot_5'],
        ];

        if ($request->hasFile('foto_menu')) {
            if ($menu->foto_menu) {
                Storage::disk('public')->delete($menu->foto_menu);
            }
            $payload['foto_menu'] = $this->storeFoto(
                $request->file('foto_menu'),
                $menu->sppg_id,
                $menu->tanggal->toDateString(),
            );
        } elseif ($request->boolean('hapus_foto')) {
            if ($menu->foto_menu) {
                Storage::disk('public')->delete($menu->foto_menu);
            }
            $payload['foto_menu'] = null;
        }

        $menu->update($payload);

        return redirect()->route('sppg.dashboard')
            ->with('success', 'Menu berhasil diperbarui. Siswa akan melihat menu terbaru.');
    }

    private function authorizeEdit(Request $request, Menu $menu): void
    {
        if ($menu->sppg_id !== $request->user()->sppg_id) {
            abort(403, 'Anda tidak berhak mengedit menu ini.');
        }

        if (! $menu->tanggal->isToday()) {
            abort(403, 'Menu hanya dapat diedit pada tanggal yang sama.');
        }
    }

    private function storeFoto($file, int $sppgId, string $tanggal): string
    {
        $ext = $file->getClientOriginalExtension() ?: 'jpg';
        $name = sprintf('%d_%s_%s.%s', $sppgId, $tanggal, Str::random(8), $ext);
        return $file->storeAs('menus', $name, 'public');
    }
}
