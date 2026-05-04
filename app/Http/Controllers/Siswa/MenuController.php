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
        $menuToday = Menu::with('sppg')
            ->where('sppg_id', $user->sppg_id)
            ->whereDate('tanggal', today())
            ->first();

        return view('siswa.menu', compact('menuToday'));
    }
}
