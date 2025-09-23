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
        DB::Table('action_points')->delete();
        DB::table('action_points')->insert([
            [
                'action_name' => 'solve_homework',
                'points' => 20,
                'description' => 'حل واجب',
            ],
            [
                'action_name' => 'solve_exam',
                'points' => 20,
                'description' => 'حل امتحان',
            ],
            [
                'action_name' => 'purchase_lesson',
                'points' => 20,
                'description' => 'شراء حصة',
            ],
            [
                'action_name' => 'purchase_chapter',
                'points' => 20,
                'description' => 'شراء شابتر',
            ],
            [
                'action_name' => 'purchase_course',
                'points' => 20,
                'description' => 'شراء كورس',
            ],

            [
                'action_name' => 'successful_referral',
                'points' => 150,
                'description' => 'دعوة صديق ناجحة (عبر رمز الإحالة)',
            ],
        ]);

    }
}
