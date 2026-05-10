<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'sekolah',
        'sppg_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function sppg(): BelongsTo
    {
        return $this->belongsTo(Sppg::class);
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(Feedback::class);
    }

    public function sentChats(): HasMany
    {
        return $this->hasMany(Chat::class, 'sender_id');
    }

    public function receivedChats(): HasMany
    {
        return $this->hasMany(Chat::class, 'receiver_id');
    }

    public function allergies(): BelongsToMany
    {
        return $this->belongsToMany(Allergy::class, 'user_allergies')
            ->withPivot('catatan')
            ->withTimestamps();
    }

    public function hasAllergy(): bool
    {
        return $this->allergies()->exists();
    }

    public function allergyNames(): string
    {
        return $this->allergies->map(function ($a) {
            $note = $a->pivot->catatan ?? null;
            if ($a->slug === 'lainnya' && $note) {
                return $a->name . ' (' . $note . ')';
            }
            return $a->name;
        })->implode(', ');
    }

    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    public function isSppg(): bool
    {
        return $this->role === 'sppg';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function getInitialsAttribute(): string
    {
        $parts = preg_split('/\s+/', trim($this->name));
        $first = mb_substr($parts[0] ?? '', 0, 1);
        $second = mb_substr($parts[1] ?? ($parts[0] ?? ''), count($parts) > 1 ? 0 : 1, 1);
        return strtoupper($first . $second);
    }

    public static function generateUsername(string $name, ?string $sekolah): string
    {
        $first = strtolower(preg_replace('/[^a-zA-Z]/', '', explode(' ', trim($name))[0] ?? 'user'));
        $school = strtolower($sekolah ?? 'mbg');
        $rand = str_pad((string) random_int(0, 999), 3, '0', STR_PAD_LEFT);
        $base = "{$first}_{$school}_{$rand}";
        while (self::where('username', $base)->exists()) {
            $rand = str_pad((string) random_int(0, 999), 3, '0', STR_PAD_LEFT);
            $base = "{$first}_{$school}_{$rand}";
        }
        return $base;
    }
}
