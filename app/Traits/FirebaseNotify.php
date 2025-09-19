<?php

namespace App\Traits;

use App\Notifications\FirebasePushNotification;
use Illuminate\Support\Facades\Notification;

trait FirebaseNotify
{
    public function sendFirebaseNotification($users, $title, $body)
    {
        if (!is_iterable($users)) {
            $users = [$users];
        }

        Notification::send($users, new FirebasePushNotification($title, $body));
    }

    public function sendAndStoreFirebaseNotification($users, $title, $body, $data = [])
    {
        if (!is_iterable($users)) {
            $users = [$users];
        }

        Notification::send($users, new FirebasePushNotification($title, $body, $data));
    }
}
