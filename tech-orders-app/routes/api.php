<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Teste simples pra ver se o api.php está sendo carregado
Route::get('/ping', function () {
    return response()->json(['pong' => true]);
});

// Healthcheck do webhook (GET)
Route::get('/webhooks/luna', function (Request $request) {
    return response()->json(['status' => 'ok']);
});

// Webhook real (POST) – por enquanto só loga e responde 200
Route::post('/webhooks/luna', function (Request $request) {
    \Log::info('Webhook Luna recebido', [
        'headers' => $request->headers->all(),
        'body'    => $request->all(),
    ]);

    return response()->json(['ok' => true]);
});
