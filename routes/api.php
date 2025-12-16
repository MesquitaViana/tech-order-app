<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\LunaWebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Teste simples
Route::get('/ping', function () {
    return response()->json(['pong' => true]);
});

// Healthcheck do webhook (GET)
Route::get('/webhooks/luna', function () {
    return response()->json(['status' => 'ok']);
});

// Webhook REAL (POST) → controller
Route::post('/webhooks/luna', [LunaWebhookController::class, 'handle']);
