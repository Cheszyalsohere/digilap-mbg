<?php

namespace Database\Seeders;

use App\Models\Sppg;
use Illuminate\Database\Seeder;

class SppgSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name' => 'SPPG Kalianyar',   'lokasi' => 'Kalianyar, Bangil, Pasuruan'],
            ['name' => 'SPPG Gempeng',     'lokasi' => 'Gempeng, Bangil, Pasuruan'],
            ['name' => 'SPPG Gajahbendo',  'lokasi' => 'Gajahbendo, Beji, Pasuruan'],
        ];

        foreach ($data as $row) {
            Sppg::firstOrCreate(['name' => $row['name']], $row);
        }
    }
}
