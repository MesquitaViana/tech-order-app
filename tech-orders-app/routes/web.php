<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Customer\AccountController as CustomerAccountController;


Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function () {
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::post('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
});

Route::prefix('minha-conta')->name('customer.')->group(function () {
    // Login
    Route::get('/login', [CustomerAccountController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [CustomerAccountController::class, 'login'])->name('login.post');

    // Logout
    Route::post('/logout', [CustomerAccountController::class, 'logout'])->name('logout');

    // Rotas protegidas
    Route::group([], function () {
        Route::get('/pedidos', [CustomerAccountController::class, 'orders'])->name('orders');
        Route::get('/pedidos/{id}', [CustomerAccountController::class, 'showOrder'])->name('orders.show');
    });
});
