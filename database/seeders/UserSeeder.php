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

        $sekolahMap = [
            'SPPG Kalirejo' => 'SMANBA',
            'SPPG Rembang'  => 'NESABA',
            'SPPG Bangil'   => 'MANSAPAS',
        ];

        $sppgs = Sppg::all();
        foreach ($sppgs as $sppg) {
            $sekolah = $sekolahMap[$sppg->name] ?? null;

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

            $namaDepan = ['Budi', 'Siti', 'Andi', 'Rina', 'Dimas', 'Putri', 'Bagus', 'Ayu', 'Fajar', 'Lestari',
                          'Reza', 'Yoga', 'Dewi', 'Hadi', 'Sari', 'Bayu', 'Lina', 'Faisal', 'Nadia', 'Iqbal',
                          'Sinta', 'Galang', 'Hesti', 'Joko', 'Mira'];
            $namaBelakang = ['Pratama', 'Saputra', 'Wijaya', 'Permana', 'Hartono', 'Setiawan', 'Lestari',
                             'Anggraini', 'Rahmawati', 'Kurniawan', 'Maulana', 'Pertiwi', 'Putra', 'Cahyadi',
                             'Susanti', 'Hidayat', 'Nugroho', 'Halim', 'Kusuma', 'Wibowo'];

            for ($i = 0; $i < 50; $i++) {
                $depan = $namaDepan[array_rand($namaDepan)];
                $belakang = $namaBelakang[array_rand($namaBelakang)];
                $name = "$depan $belakang";

                $username = User::generateUsername($name, $sekolah);

                User::create([
                    'name'     => $name,
                    'username' => $username,
                    'email'    => $username . '@digilap.test',
                    'password' => 'password',
                    'role'     => 'siswa',
                    'sekolah'  => $sekolah,
                    'sppg_id'  => $sppg->id,
                ]);
            }
        }
    }
}
