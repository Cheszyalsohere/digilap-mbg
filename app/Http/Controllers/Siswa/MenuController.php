<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $user->loadMissing('allergies');

        $menuToday = Menu::with('sppg')
            ->where('sppg_id', $user->sppg_id)
            ->whereDate('tanggal', today())
            ->first();

        $menuView = null;
        if ($menuToday) {
            $menuView = $menuToday->getMenuForUser($user);
        }

        return view('siswa.menu', compact('menuToday', 'menuView'));
    }
}
