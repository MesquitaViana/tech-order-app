<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\AccountController as CustomerAccountController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\SubscriptionController as AdminSubscriptionController;

// (Opcional) redirecionar / para login do cliente
Route::get('/', function () {
    return redirect()->route('customer.login');
});

/*
|--------------------------------------------------------------------------
| Rotas da Minha Conta (Cliente)
|--------------------------------------------------------------------------
*/

Route::prefix('minha-conta')->name('customer.')->group(function () {

    // Login
    Route::get('/login', [CustomerAccountController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [CustomerAccountController::class, 'login'])
        ->middleware('throttle:customer-login')
        ->name('login.post');

    // Logout
    Route::post('/logout', [CustomerAccountController::class, 'logout'])
        ->name('logout');

    // Rotas protegidas pelo middleware do cliente
    Route::middleware('customer.auth')->group(function () {

        /**
         * Pedidos
         */
        Route::get('/pedidos', [CustomerAccountController::class, 'orders'])
            ->name('orders');

        Route::get('/pedidos/{id}', [CustomerAccountController::class, 'showOrder'])
            ->name('orders.show');

        // Aceite do termo de entrega para pedidos com rastreio
        Route::post('/pedidos/{id}/aceitar-termos-rastreio', [CustomerAccountController::class, 'acceptTrackingTerms'])
            ->name('orders.acceptTrackingTerms');

        /**
         * Minhas Assinaturas
         */
        Route::get('/assinaturas', [CustomerAccountController::class, 'subscriptions'])
            ->name('subscriptions');

        // Recomendação de Box via IA (chamada via AJAX ou form)
        Route::post('/assinaturas/recomendar', [CustomerAccountController::class, 'recommendSubscription'])
            ->name('subscriptions.recommend');

        // Assistente de assinaturas (questionário)
        Route::get('/assinaturas/assistente', [CustomerAccountController::class, 'showSubscriptionAssistant'])
            ->name('subscriptions.assistant');

        // Processa questionário e mostra recomendações
        Route::post('/assinaturas/assistente', [CustomerAccountController::class, 'processSubscriptionAssistant'])
            ->name('subscriptions.assistant.process');
    });
});

/*
|--------------------------------------------------------------------------
| Rotas Admin (Painel)
|--------------------------------------------------------------------------
*/

// LOGIN ADMIN – público
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [AdminAuthController::class, 'login'])
        ->middleware('throttle:admin-login')
        ->name('login.post');
});

// ÁREA ADMIN protegida
Route::middleware('admin.auth:admin')->prefix('admin')->name('admin.')->group(function () {

    Route::post('/logout', [AdminAuthController::class, 'logout'])
        ->name('logout');

    // Pedidos
    Route::get('/orders', [AdminOrderController::class, 'index'])
        ->name('orders.index');

    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])
        ->name('orders.show');

    Route::post('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])
        ->name('orders.updateStatus');

    // Atualizar código de rastreio
    Route::post('/orders/{id}/tracking', [AdminOrderController::class, 'updateTracking'])
        ->name('orders.updateTracking');

    // Assinaturas (Painel Admin)
    Route::get('/subscriptions', [AdminSubscriptionController::class, 'index'])
        ->name('subscriptions.index');

    Route::get('/subscriptions/create', [AdminSubscriptionController::class, 'create'])
        ->name('subscriptions.create');

    Route::post('/subscriptions', [AdminSubscriptionController::class, 'store'])
        ->name('subscriptions.store');

    Route::get('/subscriptions/{id}/edit', [AdminSubscriptionController::class, 'edit'])
        ->name('subscriptions.edit');

    Route::post('/subscriptions/{id}', [AdminSubscriptionController::class, 'update'])
        ->name('subscriptions.update');
});
