<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        for($i = 0; $i < 5; $i++) {
            DB::table('categories')->insert([
                'category_name' => "Category $i",
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
