<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'site_name',
                'value' => 'My Awesome Site',
                'description' => 'The name of the website',
            ],
            [
                'key' => 'contact_email',
                'value' => 'contact@example.com',
                'description' => 'Contact email address for support',
            ],
            [
                'key' => 'referral points',
                'value' => 10,
                'description' => 'points that student get when another student registered with his referral code',
            ],
            [
                'key' => 'general plan price',
                'value' => 10,
                'description' => 'the price of general plan for students that he can access to all courses',
            ],
            [
                'key' => 'Instapay Mobile Number',
                'value' => 10,
                'description' => 'the number that you will receive payments on it by instapay',
            ],
            [
                'key' => 'Wallet Mobile Number',
                'value' => 10,
                'description' => 'The number that students will pay to charge their wallet using wallets like vodafone cash, etisalat cash, we pay',
            ],
            // Add as many as you like
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'description' => $setting['description']]
            );
        }

    }
}
