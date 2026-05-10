<?php

namespace App\Http\Controllers\Siswa;

use App\Concerns\LogsActivity;
use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Menu;
use App\Models\User;
use App\Notifications\NewFeedbackReceived;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    use LogsActivity;

    public function index(Request $request): View
    {
        $feedbacks = Feedback::with('menu')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(15);

        return view('siswa.feedback.index', compact('feedbacks'));
    }

    public function create(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        $user->loadMissing('allergies');

        $menu = Menu::where('sppg_id', $user->sppg_id)
            ->whereDate('tanggal', today())
            ->first();

        if (! $menu) {
            return redirect()->route('siswa.dashboard')
                ->with('error', 'Menu hari ini belum tersedia.');
        }

        $alreadyExists = Feedback::where('user_id', $user->id)
            ->where('menu_id', $menu->id)
            ->exists();

        if ($alreadyExists) {
            return redirect()->route('siswa.dashboard')
                ->with('error', 'Anda sudah mengisi feedback untuk hari ini.');
        }

        $menuView = $menu->getMenuForUser($user);

        return view('siswa.feedback.create', compact('menu', 'menuView'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'menu_id'  => ['required', 'exists:menus,id'],
            'rating'   => ['required', 'integer', 'min:1', 'max:5'],
            'komentar' => ['nullable', 'string', 'max:1000'],
            'foto'     => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $user = $request->user();
        $menu = Menu::findOrFail($data['menu_id']);

        if ($menu->sppg_id !== $user->sppg_id) {
            abort(403);
        }

        if (Feedback::where('user_id', $user->id)->where('menu_id', $menu->id)->exists()) {
            return redirect()->route('siswa.dashboard')
                ->with('error', 'Anda sudah mengisi feedback untuk hari ini.');
        }

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('feedbacks', 'public');
        }

        $feedback = Feedback::create([
            'user_id'  => $user->id,
            'menu_id'  => $menu->id,
            'foto'     => $fotoPath,
            'rating'   => $data['rating'],
            'komentar' => $data['komentar'] ?? null,
        ]);

        $sppgStaff = User::where('role', 'sppg')->where('sppg_id', $menu->sppg_id)->get();
        Notification::send($sppgStaff, new NewFeedbackReceived($feedback));

        $this->logActivity(
            "Mengirim feedback menu {$menu->tanggal->format('Y-m-d')} — rating {$feedback->rating}★",
            $feedback->komentar,
            $feedback
        );

        return redirect()->route('siswa.dashboard')
            ->with('success', 'Terima kasih! Feedback Anda telah tercatat.');
    }
}
