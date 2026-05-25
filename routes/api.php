<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\SubscriptionController;


// 1. Rute Services
Route::apiResource("services", ServiceController::class);
Route::patch("services/{service}/activate", [ServiceController::class, "activate"]);
Route::patch("services/{service}/deactivate", [ServiceController::class, "deactivate"]);

// 2. Rute Customers
Route::apiResource("customers", CustomerController::class);
Route::patch("customers/{customer}/activate", [CustomerController::class, "activate"]);
Route::patch("customers/{customer}/deactivate", [CustomerController::class, "deactivate"]);

// 3. Rute Subscriptions (Gabungkan update ke dalam apiResource)
Route::apiResource("subscriptions", SubscriptionController::class)
    ->only([
        "index",
        "store",
        "show",
        "update" // <-- Tinggal nambahin ini, Ji! Otomatis bikin rute PUT /api/subscriptions/{subscription}
    ]);

// Rute untuk menghapus data (opsional, tergantung kebutuhan)   
Route::delete('/customers/{id}', [CustomerController::class, 'destroy']);
Route::delete('/services/{id}', [ServiceController::class, 'destroy']);