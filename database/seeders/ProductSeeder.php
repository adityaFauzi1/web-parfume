<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    use GeneratesPlaceholderImages;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            ['Bleu de Chanel EDP', 'Pria', 'Perpaduan aromatik jeruk, kayu cedar dan dupa yang maskulin namun elegan. Cocok untuk acara formal maupun harian.', 1850000, '100 ml', 24, true, ['#0f172a', '#334155']],
            ['Dior Sauvage EDP', 'Pria', 'Kesegaran bergamot Calabria yang dipadukan ambroxan liar. Wangi liar yang bebas dan penuh karakter.', 1950000, '100 ml', 18, true, ['#0c4a6e', '#38bdf8']],
            ['Chanel No. 5 EDP', 'Wanita', 'Ikon abadi dengan wangi aldehid, ylang-ylang dan vanilla. Simbol keanggunan feminin sepanjang masa.', 2100000, '100 ml', 12, true, ['#831843', '#fbcfe8']],
            ['Miss Dior Blooming Bouquet', 'Wanita', 'Buket bunga peony, rose dan white musk yang lembut. Fresh dan romantis untuk keseharian.', 1650000, '100 ml', 20, false, ['#f472b6', '#fda4af']],
            ['Jo Malone Lime Basil & Mandarin', 'Unisex', 'Aroma jeruk mandarin segar berpadu basil dan amber. Menghadirkan energi segar penuh semangat.', 1600000, '100 ml', 15, true, ['#14532d', '#4ade80']],
            ['Tom Ford Oud Wood', 'Niche', 'Kayu oud eksotis dipadukan rosewood, sandalwood dan vanilla. Mewah, dalam dan misterius.', 3500000, '50 ml', 8, true, ['#451a03', '#78350f']],
            ['Creed Aventus', 'Niche', 'Nanap hitam, bergamot, blackcurrant dan oakmoss. Wangi kesuksesan yang penuh percaya diri.', 4200000, '100 ml', 6, true, ['#1c1917', '#a8a29e']],
            ['YSL Libre EDP', 'Wanita', 'Perpaduan lavender Prancis, bunga orange blossom dan vanilla Madagaskar. Bebas dan berani.', 1750000, '90 ml', 14, false, ['#7e22ce', '#c4b5fd']],
            ['Versace Eros', 'Pria', 'Mint, green apple dan tonka bean dengan sentuhan vanilla. Berani dan menggoda seperti dewa cinta.', 1250000, '100 ml', 30, false, ['#1e3a8a', '#60a5fa']],
            ['Ariana Grande Cloud EDP', 'Wanita', 'Wangi creamy dari lavender, pear, praline dan musk. Lembut, manis dan nyaman dipakai sehari-hari.', 950000, '100 ml', 35, false, ['#64748b', '#e2e8f0']],
            ['Le Labo Santal 33', 'Unisex', 'Sandalwood, cardamom, iris dan violet. Aroma ikonik asal Brooklyn yang hangat dan adiktif.', 2800000, '100 ml', 9, true, ['#292524', '#d6d3d1']],
            ['Burberry Hero EDP', 'Pria', 'Three cedars dari Atlas, Virginia dan Himalaya. Maskulin, segar dan penuh kesan pahlawan.', 1700000, '100 ml', 21, false, ['#3f3f46', '#a1a1aa']],
        ];

        foreach ($products as [$name, $categoryName, $description, $price, $size, $stock, $featured, $colors]) {
            $category = Category::where('name', $categoryName)->first();
            $slug = Str::slug($name);
            $image = "images/products/{$slug}.svg";

            $this->makePlaceholder(
                public_path($image),
                $name,
                $colors[0],
                $colors[1]
            );

            Product::updateOrCreate(['slug' => $slug], [
                'category_id' => $category->id,
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'purchase_price' => (int) (round($price * 0.65 / 1000) * 1000),
                'size' => $size,
                'gender' => $categoryName,
                'stock' => $stock,
                'min_stock' => 5,
                'image' => $image,
                'is_featured' => $featured,
            ]);
        }
    }
}