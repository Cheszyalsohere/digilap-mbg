<?php

namespace App\Notifications;

use App\Models\Feedback;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewFeedbackReceived extends Notification
{
    use Queueable;

    public function __construct(public Feedback $feedback)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'feedback_id' => $this->feedback->id,
            'siswa_name'  => $this->feedback->user?->name,
            'rating'      => $this->feedback->rating,
            'komentar'    => $this->feedback->komentar,
            'message'     => "{$this->feedback->user?->name} mengirim feedback baru — rating {$this->feedback->rating}★",
            'url'         => '/sppg/laporan',
        ];
    }
}
