<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Exception\Messaging\InvalidMessage;
use Kreait\Firebase\Exception\FirebaseException;
use Throwable;

class DirectFcmTest extends Command
{
    protected $signature = 'fcm:direct-test {token}';
    protected $description = 'Sends a direct test notification using kreait/firebase-php.';

    public function handle()
    {
        $deviceToken = $this->argument('token');

        try {
            // Use environment variable
            $serviceAccountPath = base_path('fcm.json');
            // Clear any potential credential cache
            if (function_exists('opcache_reset')) {
                opcache_reset();
            }

            $factory = (new Factory)
                ->withServiceAccount($serviceAccountPath);

            $messaging = $factory->createMessaging();
            $this->info("Firebase Messaging client created successfully.");

            $message = CloudMessage::withTarget('token', $deviceToken)
                ->withNotification([
                    'title' => 'Test Notification',
                    'body' => 'Testing FCM from Laravel'
                ]);

            $result = $messaging->send($message);
            $this->info('✓ Notification sent successfully!');

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());

            // Additional debugging
            $this->error('Error class: ' . get_class($e));
            if (method_exists($e, 'errors')) {
                $this->error('Details: ' . json_encode($e->errors(), JSON_PRETTY_PRINT));
            }
        }

        return 0;
    }
}
