<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Storage::deleteDirectory('products');
        Storage::makeDirectory('products');

        User::factory()->create([
            'name' => 'Test',
            'last_name' => 'User',
            'document_type' => 1,
            'document_number' => 12345678,
            'email' => 'test@example.com',
            'phone' => '123456789',
            'password' => bcrypt('123456789'),
        ]);

        User::factory(20)->create();

        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            CategorySeeder::class,
            OptionSeeder::class,
        ]);

        // Product::factory(150)->create();
    }
}
