<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Type\Time;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        for($i = 0; $i < 5; $i++) {
            DB::table('products')->insert([
                'product_name' => "Product $i",
                'price' => mt_rand(5000, 200000),
                'stock' => mt_rand(1, 30),
                'category_id' => mt_rand(1, 5),
                'brand_id' => mt_rand(1, 5),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
