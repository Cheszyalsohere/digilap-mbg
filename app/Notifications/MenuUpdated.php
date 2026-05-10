<?php

namespace App\Notifications;

use App\Models\Menu;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MenuUpdated extends Notification
{
    use Queueable;

    public function __construct(public Menu $menu, public string $sppgName)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'menu_id'   => $this->menu->id,
            'sppg_name' => $this->sppgName,
            'message'   => "Menu hari ini sudah diperbarui oleh {$this->sppgName}",
            'url'       => '/menu',
        ];
    }
}
