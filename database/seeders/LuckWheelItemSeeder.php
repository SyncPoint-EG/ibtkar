<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LuckWheelItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('luck_wheel_items')->insert([
            [
                'gift' => '10 points',
                'appearance_percentage' => 50,
            ],
            [
                'gift' => '50 points',
                'appearance_percentage' => 20,
            ],
            [
                'gift' => '100 points',
                'appearance_percentage' => 10,
            ],
            [
                'gift' => 'Free course',
                'appearance_percentage' => 5,
            ],
            [
                'gift' => 'No prize',
                'appearance_percentage' => 15,
            ],
        ]);
    }
}