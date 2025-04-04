<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Family;
use App\Models\Subcategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $categories = [
            'Hombre' => [
                'Remeras',
                'Pantalones',
                'Bermudas',
                'Ropa interior',
                'Camperas',
                'Camisas',
                'Accesorios',
            ],
            'Mujer' => [
                'Remeras',
                'Pantalones',
                'Shorts',
                'Polleras',
                'Camperas',
                'Camisas',
                'Accesorios',
            ],
        ];



        foreach ($categories as $category => $subcategories) {
            $category = Category::create([
                'name' => $category,
            ]);

            foreach ($subcategories as $subcategory) {
                Subcategory::create([
                    'name' => $subcategory,
                    'category_id' => $category->id,
                ]);
            }
        }
    }
}
