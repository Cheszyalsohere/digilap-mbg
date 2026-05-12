<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'sppg_id', 'tanggal',
        'slot_1', 'slot_2', 'slot_3', 'slot_4', 'slot_5',
        'foto_menu',
        'has_alternatif',
        'alt_slot_1', 'alt_slot_2', 'alt_slot_3', 'alt_slot_4', 'alt_slot_5',
        'alt_keterangan',
    ];

    protected $casts = [
        'tanggal'        => 'date',
        'has_alternatif' => 'boolean',
    ];

    public function sppg(): BelongsTo
    {
        return $this->belongsTo(Sppg::class);
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(Feedback::class);
    }

    public function slots(): array
    {
        return [
            'Slot 1' => $this->slot_1,
            'Slot 2' => $this->slot_2,
            'Slot 3' => $this->slot_3,
            'Slot 4' => $this->slot_4,
            'Slot 5' => $this->slot_5,
        ];
    }

    public function altSlots(): array
    {
        return [
            'Slot 1' => $this->alt_slot_1 ?: $this->slot_1,
            'Slot 2' => $this->alt_slot_2 ?: $this->slot_2,
            'Slot 3' => $this->alt_slot_3 ?: $this->slot_3,
            'Slot 4' => $this->alt_slot_4 ?: $this->slot_4,
            'Slot 5' => $this->alt_slot_5 ?: $this->slot_5,
        ];
    }

    public function getMenuForUser(User $user): array
    {
        if ($this->has_alternatif && $user->hasAllergy()) {
            return [
                'slots'         => $this->altSlots(),
                'is_alternatif' => true,
                'keterangan'    => $this->alt_keterangan,
            ];
        }

        return [
            'slots'         => $this->slots(),
            'is_alternatif' => false,
            'keterangan'    => null,
        ];
    }
}
