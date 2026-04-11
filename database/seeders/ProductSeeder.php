<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Ambil kategori
        $ruangTamu   = Category::where('slug', 'ruang-tamu')->first();
        $kamarTidur  = Category::where('slug', 'kamar-tidur')->first();
        $ruangMakan  = Category::where('slug', 'ruang-makan')->first();
        $pencahayaan = Category::where('slug', 'pencahayaan')->first();

        $products = [
            // Produk Terpopuler
            [
                'category_id' => $ruangTamu->id,
                'name' => 'Kursi Lengan Nordik',
                'slug' => 'kursi-lengan-nordik',
                'description' => 'Kursi dengan desain Nordik yang minimalis, berbahan linen abu terang, rangka kayu jati.',
                'price' => 4250000,
                'stock' => 10,
                'image' => 'products/kursi-nordik.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => $ruangTamu->id,
                'name' => 'Meja Samping Walnut',
                'slug' => 'meja-samping-walnut',
                'description' => 'Meja samping dari kayu walnut solid, finishing natural.',
                'price' => 1850000,
                'stock' => 15,
                'image' => 'products/meja-walnut.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => $pencahayaan->id,
                'name' => 'Lampu Gantung Aruma',
                'slug' => 'lampu-gantung-aruma',
                'description' => 'Lampu gantung dengan desain modern, material metal hitam dan kaca opal.',
                'price' => 2100000,
                'stock' => 8,
                'image' => 'products/lampu-aruma.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => $ruangMakan->id,
                'name' => 'Rak Kayu Modular',
                'slug' => 'rak-kayu-modular',
                'description' => 'Rak dinding modular dari kayu ek, bisa disusun sesuai kebutuhan.',
                'price' => 3500000,
                'stock' => 5,
                'image' => 'products/rak-modular.jpg',
                'is_active' => true,
            ],
            // Eksplor Produk
            [
                'category_id' => $ruangTamu->id,
                'name' => 'Sofa Velvet Serenity',
                'slug' => 'sofa-velvet-serenity',
                'description' => 'Sofa 3 seater berbahan velvet hijau sage, bantalan tebal, rangka kayu.',
                'price' => 12500000,
                'stock' => 4,
                'image' => 'products/sofa-velvet.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => $ruangTamu->id,
                'name' => 'Meja Kopi Horizon',
                'slug' => 'meja-kopi-horizon',
                'description' => 'Meja kopi dengan meja kayu walnut solid dan kaki besi hitam.',
                'price' => 4200000,
                'stock' => 7,
                'image' => 'products/meja-kopi.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => $pencahayaan->id,
                'name' => 'Lampu Lantai Sulis',
                'slug' => 'lampu-lantai-sulis',
                'description' => 'Lampu lantai dengan desain minimalis, material matte black steel.',
                'price' => 1950000,
                'stock' => 12,
                'image' => 'products/lampu-sulis.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => $kamarTidur->id,
                'name' => 'Karpet Wol Tufted',
                'slug' => 'karpet-wol-tufted',
                'description' => 'Karpet wol dengan warna broken white, ukuran 200x300cm.',
                'price' => 5400000,
                'stock' => 6,
                'image' => 'products/karpet-wol.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => $ruangTamu->id,
                'name' => 'Kursi Lounge Nara',
                'slug' => 'kursi-lounge-nara',
                'description' => 'Didesain untuk memberikan ketenangan, kursi lounge Nara memfasilitasi aktivitas santai dengan material pilihan.',
                'price' => 8450000,
                'stock' => 3,
                'image' => 'products/kursi-nara.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => $ruangTamu->id,
                'name' => 'Kursi Santai Aris',
                'slug' => 'kursi-santai-aris',
                'description' => 'Kursi santai dengan material kayu jati & linen sage.',
                'price' => 4250000,
                'stock' => 9,
                'image' => 'products/kursi-aris.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => $pencahayaan->id,
                'name' => 'Lampu Meja Kalla',
                'slug' => 'lampu-meja-kalla',
                'description' => 'Lampu meja dengan warna cream textured, desain klasik modern.',
                'price' => 1150000,
                'stock' => 20,
                'image' => 'products/lampu-kalla.jpg',
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['slug' => $product['slug']],
                $product
            );
        }
    }
}