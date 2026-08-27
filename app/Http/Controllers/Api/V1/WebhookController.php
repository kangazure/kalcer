<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Services\Integration\WhatsAppService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends ApiController
{
    public function __construct(protected WhatsAppService $whatsAppService)
    {
    }

    public function verifyWhatsApp(Request $request)
    {
        $mode = (string) $request->query('hub_mode');
        $token = (string) $request->query('hub_verify_token');
        $challenge = (string) $request->query('hub_challenge');

        $result = $this->whatsAppService->verifyWebhookChallenge($mode, $token, $challenge);

        if ($result === null) {
            return response('Forbidden', 403);
        }

        return response($result, 200);
    }

    public function handleWhatsApp(Request $request): JsonResponse
    {
        Log::channel('supabase')->info('WhatsApp webhook received.', $request->all());

        return $this->success(message: 'OK');
    }

    public function handleTelegram(Request $request): JsonResponse
    {
        $secretHeader = $request->header('X-Telegram-Bot-Api-Secret-Token');

        if ($secretHeader !== config('services.telegram.webhook_secret')) {
            return $this->forbidden('Invalid webhook secret.');
        }

        Log::channel('supabase')->info('Telegram webhook received.', $request->all());

        return $this->success(message: 'OK');
    }
}
