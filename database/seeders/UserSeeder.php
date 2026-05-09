<?php

namespace Database\Seeders;

use App\Models\Sppg;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name'     => 'Administrator DIGILAP',
                'email'    => 'admin@digilap.test',
                'password' => 'password',
                'role'     => 'admin',
            ]
        );

        foreach (Sppg::all() as $sppg) {
            User::firstOrCreate(
                ['username' => 'sppg_' . Str::slug($sppg->name, '_')],
                [
                    'name'     => 'Petugas ' . $sppg->name,
                    'email'    => Str::slug($sppg->name) . '@digilap.test',
                    'password' => 'password',
                    'role'     => 'sppg',
                    'sppg_id'  => $sppg->id,
                ]
            );
        }
    }
}
