<?php

use App\Http\Controllers\Api\V1\WebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Webhook Routes — /webhooks/*
|--------------------------------------------------------------------------
| Dipanggil langsung oleh server pihak ketiga (Meta/WhatsApp, Telegram).
| Verifikasi keamanan dilakukan via secret token spesifik per provider
| di level controller, bukan middleware auth standar.
*/

Route::get('/whatsapp', [WebhookController::class, 'verifyWhatsApp'])->name('webhooks.whatsapp.verify');
Route::post('/whatsapp', [WebhookController::class, 'handleWhatsApp'])->name('webhooks.whatsapp.handle');
Route::post('/telegram', [WebhookController::class, 'handleTelegram'])->name('webhooks.telegram.handle');
