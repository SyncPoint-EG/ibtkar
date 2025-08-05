<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActionPointsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('action_points')->insert([
            [
                'action_name' => 'complete_homework',
                'points' => 20,
                'description' => 'إكمال واجب',
            ],
            [
                'action_name' => 'high_score_exam',
                'points' => 100,
                'description' => 'تحقيق درجة عالية في امتحان',
            ],
            [
                'action_name' => 'successful_referral',
                'points' => 150,
                'description' => 'دعوة صديق ناجحة (عبر رمز الإحالة)',
            ],
        ]);

    }
}
