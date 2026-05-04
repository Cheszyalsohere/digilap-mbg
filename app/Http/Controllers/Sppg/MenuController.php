<?php

namespace App\Http\Controllers\Sppg;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            'slot_1' => ['required', 'string', 'max:255'],
            'slot_2' => ['required', 'string', 'max:255'],
            'slot_3' => ['required', 'string', 'max:255'],
            'slot_4' => ['required', 'string', 'max:255'],
            'slot_5' => ['required', 'string', 'max:255'],
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

        Menu::create([
            'sppg_id' => $user->sppg_id,
            'tanggal' => $today,
            ...$data,
        ]);

        return redirect()->route('sppg.dashboard')
            ->with('success', 'Menu hari ini berhasil disimpan.');
    }
}
