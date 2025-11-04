<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;

use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class FirebasePushNotification extends Notification
{
    use Queueable;

    private $title;

    private $body;

    private $data;

    /**
     * Create a new notification instance.
     */
    public function __construct($title, $body, $data = [])
    {
        $this->title = $title;
        $this->body = $body;
        $this->data = $this->stringifyData($data);
    }

    /**
     * Get the notification\'s delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [FcmChannel::class, 'database'];
    }

    /**
     * Get the fcm representation of the notification.
     */
    public function toFcm(object $notifiable): FcmMessage
    {
        return (new FcmMessage(notification: new FcmNotification(
            title: $this->title,
            body: $this->body
        )))->data($this->data);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return array_merge($this->data, [
            'title' => $this->title,
            'body' => $this->body,
        ]);
    }

    /**
     * Ensure all data payload values are strings as required by FCM.
     */
    private function stringifyData(array $data): array
    {
        return collect($data)
            ->map(function ($value) {
                if ($value instanceof \Stringable) {
                    return (string) $value;
                }

                if (is_bool($value)) {
                    return $value ? 'true' : 'false';
                }

                if (is_scalar($value)) {
                    return (string) $value;
                }

                if (is_null($value)) {
                    return '';
                }

                return json_encode($value);
            })
            ->all();
    }
}
