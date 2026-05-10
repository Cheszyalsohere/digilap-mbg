<?php

namespace Database\Seeders;

use App\Models\Allergy;
use Illuminate\Database\Seeder;

class AllergySeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Susu & Produk Susu', 'slug' => 'susu'],
            ['name' => 'Telur',               'slug' => 'telur'],
            ['name' => 'Kacang',              'slug' => 'kacang'],
            ['name' => 'Seafood & Ikan',      'slug' => 'seafood'],
            ['name' => 'Gluten/Gandum',       'slug' => 'gluten'],
            ['name' => 'Lainnya',             'slug' => 'lainnya'],
        ];

        foreach ($items as $item) {
            Allergy::updateOrCreate(['slug' => $item['slug']], $item);
        }
    }
}
