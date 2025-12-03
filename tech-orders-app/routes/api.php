<?php

use App\Http\Controllers\Webhook\LunaWebhookController;

Route::post('/webhooks/luna', [LunaWebhookController::class, 'handle']);
