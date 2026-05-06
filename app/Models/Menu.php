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
        'sppg_id', 'tanggal', 'slot_1', 'slot_2', 'slot_3', 'slot_4', 'slot_5', 'foto_menu',
    ];

    protected $casts = [
        'tanggal' => 'date',
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
            'Nasi'           => $this->slot_1,
            'Buah'           => $this->slot_2,
            'Protein Nabati' => $this->slot_3,
            'Protein Hewani' => $this->slot_4,
            'Susu'           => $this->slot_5,
        ];
    }
}
