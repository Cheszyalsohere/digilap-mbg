<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class InvitationCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'sekolah', 'sppg_id', 'is_active', 'expires_at', 'used_count',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'expires_at' => 'datetime',
        'used_count' => 'integer',
    ];

    public function sppg(): BelongsTo
    {
        return $this->belongsTo(Sppg::class);
    }

    public function isUsable(): bool
    {
        if (! $this->is_active) {
            return false;
        }
        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }
        return true;
    }

    public static function generateUniqueCode(string $sekolah): string
    {
        do {
            $suffix = strtoupper(Str::random(4));
            $code = strtoupper($sekolah) . '-' . $suffix;
        } while (self::where('code', $code)->exists());

        return $code;
    }
}
