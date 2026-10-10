<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    use GeneratesPlaceholderImages;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['Pria', 'Wangi maskulin dan kuat untuk pria yang percaya diri.', ['#1f2937', '#4b5563']],
            ['Wanita', 'Aroma feminin, elegan dan menawan untuk setiap momen.', ['#9d174d', '#f9a8d4']],
            ['Unisex', 'Fragrance netral yang cocok untuk semua kalangan.', ['#065f46', '#34d399']],
            ['Niche', 'Parfum premium dengan karakter unik dan eksklusif.', ['#7c2d12', '#f97316']],
        ];

        foreach ($categories as [$name, $description, $colors]) {
            $slug = Str::slug($name);

            $this->makePlaceholder(
                public_path("images/categories/{$slug}.svg"),
                Str::upper($name),
                $colors[0],
                $colors[1]
            );

            Category::updateOrCreate(['slug' => $slug], [
                'name' => $name,
                'description' => $description,
                'image' => "images/categories/{$slug}.svg",
            ]);
        }
    }
}