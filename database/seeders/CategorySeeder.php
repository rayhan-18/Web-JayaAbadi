<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Ruang Tamu', 'slug' => 'ruang-tamu', 'is_active' => true],
            ['name' => 'Kamar Tidur', 'slug' => 'kamar-tidur', 'is_active' => true],
            ['name' => 'Ruang Makan', 'slug' => 'ruang-makan', 'is_active' => true],
            ['name' => 'Koleksi Baru', 'slug' => 'koleksi-baru', 'is_active' => true],
            ['name' => 'Inspirasi', 'slug' => 'inspirasi', 'is_active' => true],
            ['name' => 'Pencahayaan', 'slug' => 'pencahayaan', 'is_active' => true],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => $cat['slug']], // cegah duplikasi
                $cat
            );
        }
    }
}