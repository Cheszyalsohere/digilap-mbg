<?php

namespace Database\Seeders;

use App\Models\Feedback;
use App\Models\Menu;
use App\Models\Sppg;
use App\Models\User;
use Illuminate\Database\Seeder;

class FeedbackSeeder extends Seeder
{
    public function run(): void
    {
        $profiles = [
            'SPPG Kalirejo' => [
                'avg' => 2.1,
                'komentar' => [
                    'Porsi makanan kurang.',
                    'Ayam terasa tidak segar.',
                    'Sayur sudah dingin saat sampai.',
                    'Susu kadang tidak ada.',
                    'Kurang bersih wadahnya.',
                    null, null, 'Terlambat datang.',
                ],
            ],
            'SPPG Rembang' => [
                'avg' => 4.2,
                'komentar' => [
                    'Enak dan porsinya pas.',
                    'Makanan masih hangat, suka.',
                    'Susu segar dan enak.',
                    null, 'Variasi menunya bagus.', null, null,
                ],
            ],
            'SPPG Bangil' => [
                'avg' => 3.8,
                'komentar' => [
                    'Lumayan, masih enak.',
                    'Ayam kadang terlalu asin.',
                    'Buahnya manis.',
                    null, 'Porsi cukup.', null,
                ],
            ],
        ];

        foreach (Sppg::all() as $sppg) {
            $config = $profiles[$sppg->name] ?? ['avg' => 4.0, 'komentar' => [null]];
            $targetAvg = $config['avg'];

            $siswa = User::where('role', 'siswa')->where('sppg_id', $sppg->id)->get();
            $menus = Menu::where('sppg_id', $sppg->id)->whereDate('tanggal', '<', today())->get();

            foreach ($menus as $menu) {
                $sample = $siswa->random(min(20, $siswa->count()));
                foreach ($sample as $user) {
                    $rating = $this->ratingFromTarget($targetAvg);
                    $komentar = $config['komentar'][array_rand($config['komentar'])] ?? null;

                    Feedback::firstOrCreate(
                        ['user_id' => $user->id, 'menu_id' => $menu->id],
                        [
                            'rating'   => $rating,
                            'komentar' => $komentar,
                            'created_at' => $menu->tanggal->copy()->setTime(rand(11, 13), rand(0, 59)),
                            'updated_at' => $menu->tanggal->copy()->setTime(rand(11, 13), rand(0, 59)),
                        ]
                    );
                }
            }
        }
    }

    private function ratingFromTarget(float $avg): int
    {
        $r = $avg + (mt_rand(-100, 100) / 100);
        return max(1, min(5, (int) round($r)));
    }
}
