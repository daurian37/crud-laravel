<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IngredientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       DB::table("ingredients")->insert([
        ["titre" => "Œuf"],
        ["titre" => "Farine"],
        ["titre" => "Muscade"], 
       ]);
    }
}
