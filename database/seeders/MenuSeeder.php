<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Sppg;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $variations = [
            ['Nasi putih 200gr', 'Pisang ambon', 'Tempe goreng 2 ptg', 'Ayam goreng 1 ptg', 'Susu UHT 200ml'],
            ['Nasi merah 180gr', 'Apel malang',  'Tahu bacem 2 ptg',  'Telur dadar 1 btr', 'Susu coklat 200ml'],
            ['Nasi uduk 200gr',  'Jeruk',        'Tempe orek',        'Ayam suwir',        'Susu vanilla 200ml'],
            ['Nasi goreng 220gr','Semangka',     'Tahu isi 2 ptg',    'Ikan tongkol',      'Susu strawberry 200ml'],
            ['Nasi kuning 200gr','Pepaya',       'Tempe mendoan',     'Telur balado',      'Susu UHT 200ml'],
            ['Nasi putih 200gr', 'Mangga',       'Tahu goreng',       'Daging cincang',    'Susu UHT 200ml'],
            ['Nasi merah 180gr', 'Pisang raja',  'Tempe kering',      'Ayam panggang',     'Susu coklat 200ml'],
        ];

        foreach (Sppg::all() as $sppg) {
            for ($i = 29; $i >= 0; $i--) {
                $tanggal = Carbon::today()->subDays($i);
                $v = $variations[$i % count($variations)];

                Menu::firstOrCreate(
                    ['sppg_id' => $sppg->id, 'tanggal' => $tanggal->toDateString()],
                    [
                        'slot_1' => $v[0],
                        'slot_2' => $v[1],
                        'slot_3' => $v[2],
                        'slot_4' => $v[3],
                        'slot_5' => $v[4],
                    ]
                );
            }
        }
    }
}
