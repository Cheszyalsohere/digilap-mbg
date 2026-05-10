<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'target_type',
        'target_id',
        'description',
        'ip_address',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getCategoryAttribute(): string
    {
        $a = mb_strtolower($this->action);
        if (str_contains($a, 'hapus') || str_contains($a, 'nonaktif')) return 'destroy';
        if (str_contains($a, 'edit')) return 'edit';
        if (str_contains($a, 'buat') || str_contains($a, 'registrasi') || str_contains($a, 'menginput') || str_contains($a, 'mereset')) return 'create';
        return 'default';
    }
}
