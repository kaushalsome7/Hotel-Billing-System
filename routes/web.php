<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\RoomServiceController;
use App\Http\Controllers\BillingController;

// ── Redirect root → login ──
Route::get('/', fn() => redirect()->route('login'));

// ── Auth routes (guests only) ──
Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])
     ->middleware('auth')
     ->name('logout');

// ── Protected routes (must be logged in) ──
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Customers — Create, Read, Delete only (SRS: no update allowed)
    Route::get('/customers',           [CustomerController::class, 'index'])  ->name('customers.index');
    Route::get('/customers/create',    [CustomerController::class, 'create']) ->name('customers.create');
    Route::post('/customers',          [CustomerController::class, 'store'])  ->name('customers.store');
    Route::get('/customers/{customer}',[CustomerController::class, 'show'])   ->name('customers.show');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    // Rooms — Full CRUD
    Route::resource('rooms', RoomController::class)->only(['index','store','update','destroy']);

    // Restaurant items — Full CRUD
    Route::resource('restaurant', RestaurantController::class)->only(['index','store','update','destroy']);

    // Room Services — Full CRUD
    Route::resource('room-services', RoomServiceController::class)->only(['index','store','update','destroy']);

    // Billing
    Route::get('/billing',                                      [BillingController::class, 'index'])             ->name('billing.index');
    Route::get('/billing/{customer}',                           [BillingController::class, 'show'])              ->name('billing.show');
    Route::post('/billing/{customer}/orders',                   [BillingController::class, 'addOrder'])          ->name('billing.addOrder');
    Route::delete('/billing/{customer}/orders/{order}',         [BillingController::class, 'removeOrder'])       ->name('billing.removeOrder');
    Route::post('/billing/{customer}/service-usages',           [BillingController::class, 'addServiceUsage'])   ->name('billing.addServiceUsage');
    Route::delete('/billing/{customer}/service-usages/{usage}', [BillingController::class, 'removeServiceUsage'])->name('billing.removeServiceUsage');
    Route::post('/billing/{customer}/adjustments',              [BillingController::class, 'saveAdjustments'])   ->name('billing.saveAdjustments');

});
