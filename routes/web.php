<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\BillingInvoiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AppointmentController::class, 'index'])->name('home');

Route::resource('appointments', AppointmentController::class)
    ->only(['index', 'create', 'store', 'update']);

Route::resource('billing-invoices', BillingInvoiceController::class)
    ->only(['index', 'create', 'store', 'update']);
