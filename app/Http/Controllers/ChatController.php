<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use App\Notifications\NewChatMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $contacts = $this->loadContacts($user);

        return view('chat.index', [
            'contacts'    => $contacts,
            'activeChat'  => null,
            'messages'    => collect(),
        ]);
    }

    public function show(Request $request, User $user): View
    {
        $me = $request->user();

        if ($me->id === $user->id) {
            abort(404);
        }

        if (! $me->isAdmin() && ! $this->canChat($me, $user)) {
            abort(403);
        }

        Chat::where('sender_id', $user->id)
            ->where('receiver_id', $me->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = Chat::where(function ($q) use ($me, $user) {
            $q->where('sender_id', $me->id)->where('receiver_id', $user->id);
        })->orWhere(function ($q) use ($me, $user) {
            $q->where('sender_id', $user->id)->where('receiver_id', $me->id);
        })->orderBy('created_at')->get();

        return view('chat.index', [
            'contacts'   => $this->loadContacts($me),
            'activeChat' => $user,
            'messages'   => $messages,
        ]);
    }

    public function store(Request $request, User $user): RedirectResponse
    {
        $me = $request->user();

        if ($me->id === $user->id) {
            abort(404);
        }
        if (! $me->isAdmin() && ! $this->canChat($me, $user)) {
            abort(403);
        }

        $data = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $chat = Chat::create([
            'sender_id'   => $me->id,
            'receiver_id' => $user->id,
            'message'     => $data['message'],
        ]);

        $user->notify(new NewChatMessage($chat, $me->name));

        return redirect()->route('chat.show', $user);
    }

    private function canChat(User $me, User $other): bool
    {
        if ($me->isSiswa() && $other->isSppg()) {
            return $me->sppg_id === $other->sppg_id;
        }
        if ($me->isSppg() && $other->isSiswa()) {
            return $me->sppg_id === $other->sppg_id;
        }
        return false;
    }

    private function loadContacts(User $me)
    {
        if ($me->isSiswa()) {
            return User::where('role', 'sppg')
                ->where('sppg_id', $me->sppg_id)
                ->get()
                ->map(fn ($u) => $this->withMeta($u, $me));
        }

        if ($me->isSppg()) {
            return User::where('role', 'siswa')
                ->where('sppg_id', $me->sppg_id)
                ->orderBy('name')
                ->get()
                ->map(fn ($u) => $this->withMeta($u, $me));
        }

        return User::where('id', '!=', $me->id)
            ->orderBy('name')
            ->limit(80)
            ->get()
            ->map(fn ($u) => $this->withMeta($u, $me));
    }

    private function withMeta(User $u, User $me): User
    {
        $u->unread_count = Chat::where('sender_id', $u->id)
            ->where('receiver_id', $me->id)
            ->where('is_read', false)
            ->count();
        return $u;
    }
}
