<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Webhook\LunaWebhookController;

// Teste simples pra ver se o api.php está sendo carregado
Route::get('/ping', function () {
    return response()->json(['pong' => true]);
});

// Healthcheck do webhook (GET)
Route::get('/webhooks/luna', function (Request $request) {
    return response()->json(['status' => 'ok']);
});

// Webhook real (POST)
Route::post('/webhooks/luna', [LunaWebhookController::class, 'handle']);
