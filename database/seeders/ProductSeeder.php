<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $response = file_get_contents('https://fakestoreapi.com/products');
        $products = json_decode($response, true);

        foreach ($products as $product) {
            Product::create(
                [
                    'title' => $product['title'],
                    'price' => $product['price'],
                    'description' => $product['description'],
                    'category' => $product['category'],
                    'image' => $product['image'],
                    'rating' => json_encode($product['rating']),
                ]
            );
        }
    }
}
