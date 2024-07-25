<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');
        
        for($i = 0; $i < 5; $i++) {
            DB::table('brands')->insert([
                'brand_name' => "Brand $i",
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
