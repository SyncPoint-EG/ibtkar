<?php

namespace App\Models;

use Illuminate\Notifications\DatabaseNotification;

class Notification extends DatabaseNotification
{
    /**
     * Known notification payload types stored in the `data` column.
     *
     * @var array<int, string>
     */
    public const TYPES = [
        'dashboard_manual',
        'lesson_published',
        'payment_approved',
        'exam_result',
        'referral_reward',
    ];
}

