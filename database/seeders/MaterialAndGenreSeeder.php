<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterialAndGenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('materials')->insert([
            ['name' => 'Cotton'],
            ['name' => 'Polyester'],
            ['name' => 'Linen'],
            ['name' => 'Silk'],
            ['name' => 'Denim'],
            ['name' => 'Wool'],
        ]);

        DB::table('outfit_genres')->insert([
            ['name' => 'Casual'],
            ['name' => 'Formal'],
            ['name' => 'Sporty'],
            ['name' => 'Bohemian'],
            ['name' => 'Vintage'],
            ['name' => 'Streetwear'],
        ]);
    }
}
