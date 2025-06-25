<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public function sendWaMessage($phoneId, $accessToken, $recipient, $type, $parameters = [], $urlType = 'link', $urlLink = null, $filename = null)
    {
        $last_ten_digits = substr($recipient, -10);
        $url = "https://graph.facebook.com/v21.0/{$phoneId}/messages";

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => "+91" . $last_ten_digits,
            'type' => $type,
        ];

        switch ($type) {
            case 'template':
                $payload['template'] = $parameters;
                break;
            case 'text':
                $payload['text'] = ['body' => $parameters['body'] ?? 'Default text body'];
                break;
            case 'interactive':
                if (isset($parameters['buttons']) && is_array($parameters['buttons'])) {
                    $buttons = array_map(fn($button) => [
                        "type" => $button['type'] ?? 'reply',
                        "reply" => [
                            "id" => $button['id'] ?? 'default_id',
                            "title" => $button['title'] ?? 'Default Title'
                        ]
                    ], $parameters['buttons']);

                    $payload['interactive'] = [
                        'type' => 'button',
                        'body' => ['text' => $parameters['body'] ?? 'Please select an option'],
                        'action' => ['buttons' => $buttons]
                    ];
                }
                break;
            case 'document':
                $payload[$type] = $urlType === 'id'
                    ? ['id' => $urlLink]
                    : ['link' => $urlLink, 'filename' => $filename];
                break;
            case 'audio':
            case 'image':
            case 'video':
            case 'sticker':
                $payload[$type] = $urlType === 'id' ? ['id' => $urlLink] : ['link' => $urlLink];
                break;
            default:
                throw new \Exception("Unsupported message type: $type");
        }

        try {
            $response = Http::withToken($accessToken)->acceptJson()->post($url, $payload);

            Log::info('WhatsApp API Response:', [
                'payload' => $payload,
                'status' => $response->status(),
                'response' => $response->json(),
            ]);

            return [
                'status' => $response->successful() ? 'success' : 'error',
                'response' => $response->json(),
                'http_code' => $response->status(),
            ];
        } catch (\Exception $e) {
            Log::error('WhatsApp API Error: ' . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}
