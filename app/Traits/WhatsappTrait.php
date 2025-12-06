<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait WhatsappTrait
{
    public static function sendMsg(string $message, string $to, $lead = null): ?array
    {
        $instanceId = config('services.ultra_msg.instance_id');
        $token = config('services.ultra_msg.token');

        $phone = self::normalizePhoneNumber($to);
        if (! $instanceId || ! $token) {
            Log::warning('UltraMsg credentials are missing; skipping WhatsApp message.', [
                'to' => $phone,
            ]);

            return null;
        }

        if (! $phone) {
            Log::warning('UltraMsg WhatsApp message skipped because the recipient phone is empty.');

            return null;
        }

        try {
            $response = Http::post("https://api.ultramsg.com/{$instanceId}/messages/chat", [
                'token' => $token,
                'to' => $phone,
                'body' => $message,
            ]);

            if ($response->failed()) {
                Log::error('UltraMsg WhatsApp message failed.', [
                    'to' => $phone,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return null;
            }

            $json = $response->json();
            if (isset($json['error']) || (isset($json['status']) && $json['status'] === 'error')) {
                Log::error('UltraMsg WhatsApp API returned an error.', [
                    'to' => $phone,
                    'response' => $json,
                ]);

                return null;
            }

            return $json;
        } catch (\Throwable $exception) {
            Log::error('UltraMsg WhatsApp request threw an exception.', [
                'to' => $phone,
                'message' => $exception->getMessage(),
            ]);

            return null;
        }
    }

    protected static function normalizePhoneNumber(?string $phone): ?string
    {
        if (! $phone) {
            return null;
        }

        return preg_replace('/\s+/', '', trim($phone));
    }
}
