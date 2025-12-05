<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\AccountController as CustomerAccountController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

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
    Route::get('/login', [CustomerAccountController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [CustomerAccountController::class, 'login'])->name('login.post');

    // Logout
    Route::post('/logout', [CustomerAccountController::class, 'logout'])->name('logout');

    // Rotas protegidas pelo middleware do cliente
    Route::middleware('customer.auth')->group(function () {
        Route::get('/pedidos', [CustomerAccountController::class, 'orders'])->name('orders');
        Route::get('/pedidos/{id}', [CustomerAccountController::class, 'showOrder'])->name('orders.show');
    });
});

/*
|--------------------------------------------------------------------------
| Rotas Admin (Painel)
|--------------------------------------------------------------------------
*/

// LOGIN ADMIN – público
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
});

// ÁREA ADMIN protegida
Route::middleware('admin.auth:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');

    // 👇 ESTA AQUI É A ROTA DE STATUS – repara no name:
    Route::post('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])
        ->name('orders.updateStatus');  // NÃO colocar "admin." aqui
});

