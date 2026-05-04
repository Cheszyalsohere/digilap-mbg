<?php

namespace Database\Seeders;

use App\Models\Sppg;
use Illuminate\Database\Seeder;

class SppgSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name' => 'SPPG Kalirejo', 'lokasi' => 'Kalirejo, Lampung Tengah'],
            ['name' => 'SPPG Rembang',  'lokasi' => 'Rembang, Pasuruan'],
            ['name' => 'SPPG Bangil',   'lokasi' => 'Bangil, Pasuruan'],
        ];

        foreach ($data as $row) {
            Sppg::firstOrCreate(['name' => $row['name']], $row);
        }
    }
}
