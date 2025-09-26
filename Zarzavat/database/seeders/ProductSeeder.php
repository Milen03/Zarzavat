<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            ['name' => 'Домати', 'description' => 'Пресни градински домати', 'price' => 2.50, 'stock' => 100, 'image' => 'tomatoes.jpg', 'category' => 'Плодове'],
            ['name' => 'Краставици', 'description' => 'Зелени и свежи краставици', 'price' => 3.20, 'stock' => 80, 'image' => 'cucumbers.jpg', 'category' => 'Зеленчуци'],
            ['name' => 'Моркови', 'description' => 'Сладки моркови', 'price' => 1.80, 'stock' => 120, 'image' => 'carrots.jpg', 'category' => 'Зеленчуци'],
            ['name' => 'Спанак', 'description' => 'Свеж спанак', 'price' => 2.00, 'stock' => 60, 'image' => 'spinach.jpg', 'category' => 'Зеленчуци'],
        ];

        foreach($products as $prod){
            $category = Category::where('name', $prod['category'])->first();
            Product::create([
                 'name' => $prod['name'],
                'description' => $prod['description'],
                'price' => $prod['price'],
                'stock' => $prod['stock'],
                'image' => $prod['image'],
                'category_id' => $category->id,
            ]);
        }
    }
}
