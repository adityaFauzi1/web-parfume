<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use \Illuminate\Database\Console\Seeds\WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@maisonparfum.com',
            'password' => Hash::make('admin123'),
            'is_admin' => true,
        ]);

        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            SalesSeeder::class,
        ]);
    }
}
