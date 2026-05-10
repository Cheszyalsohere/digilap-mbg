<?php

namespace App\Notifications;

use App\Models\Chat;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewChatMessage extends Notification
{
    use Queueable;

    public function __construct(public Chat $chat, public string $senderName)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'chat_id'     => $this->chat->id,
            'sender_id'   => $this->chat->sender_id,
            'sender_name' => $this->senderName,
            'preview'     => mb_substr($this->chat->message, 0, 80),
            'message'     => "Pesan baru dari {$this->senderName}",
            'url'         => "/chat/{$this->chat->sender_id}",
        ];
    }
}
