<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RewardPointsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('reward_points')->insert([
            [
                'reward_name' => '10_percent_discount',
                'points_cost' => 500,
                'description' => 'خصم 10%',
            ],
        ]);
    }
}
