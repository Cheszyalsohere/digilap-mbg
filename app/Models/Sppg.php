<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sppg extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'lokasi'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function siswa(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'siswa');
    }

    public function staff(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'sppg');
    }

    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class);
    }
}
