<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Models\Guardian;
use App\Models\User;
use App\Notifications\FirebasePushNotification;
use Illuminate\Support\Facades\Log;

class TestFcmCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fcm:test {type} {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test FCM notification to a user, student, or guardian';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->argument('type');
        $id = $this->argument('id');
        $model = null;

        switch ($type) {
            case 'student':
                $model = Student::find($id);
                break;
            case 'guardian':
                $model = Guardian::find($id);
                break;
            case 'user':
                $model = User::find($id);
                break;
            default:
                $this->error('Invalid user type. Use "student", "guardian", or "user".');
                return 1;
        }

        if (!$model) {
            $this->error("No {$type} found with ID {$id}.");
            return 1;
        }

        // Check if the user has devices
        if (empty($model->routeNotificationForFcm())) {
            $this->warn("This {$type} (ID: {$id}) has no registered FCM device tokens. The notification will be stored in the database but not sent.");
        }

        $this->info("Sending test notification to {$type} with ID {$id}...");

//        try {
            $model->notify(new FirebasePushNotification(
                'Test Notification',
                'This is a test message from your application.',
                ['test_id' => uniqid()]
            ));
            $this->info('Notification sent successfully!');
            $this->info('Check the device and your `notifications` table in the database.');
//        } catch (\Exception $e) {
//            $this->error('An error occurred while sending the notification:');
//            $this->error($e->getMessage());
//            Log::error('FCM Test Failed: ' . $e->getMessage());
//            return 1;
//        }

        return 0;
    }
}
