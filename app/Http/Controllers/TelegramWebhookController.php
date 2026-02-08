<?php

namespace App\Http\Controllers;

use App\Services\TelegramBotService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class TelegramWebhookController extends Controller
{
    protected TelegramBotService $telegramService;

    public function __construct(TelegramBotService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    /**
     * Handle incoming webhook from Telegram
     */
    public function handle(Request $request): JsonResponse
    {
        $update = $request->all();

        Log::info('Telegram webhook received', ['update' => $update]);

        try {
            $this->telegramService->processWebhook($update);
        } catch (\Exception $e) {
            Log::error('Telegram webhook error', ['error' => $e->getMessage()]);
        }

        // Always return 200 OK to Telegram
        return response()->json(['status' => 'ok']);
    }
}
