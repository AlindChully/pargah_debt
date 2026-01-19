<?php

use Illuminate\Support\Facades\Route;
use Barryvdh\Snappy\Facades\SnappyPdf;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
Route::middleware(['auth'])->group(function () {
    //user
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    
    
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    //dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/pdf', [DashboardController::class, 'pdf'])->name('dashboard.pdf');
    Route::get('/dashboard/print', [DashboardController::class, 'print'])->name('dashboard.print');

    // حفظ الشخص الجديد
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    Route::post('/customers/{customer}/debts', [CustomerController::class, 'addDebt'])->name('customers.debts.store');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    //debts
    Route::get('customers/{customer}/debts/print', [CustomerController::class, 'print'])->name('customers.debts.print');
    Route::get('/customers/{customer}/debts/pdf', [CustomerController::class, 'pdf'])->name('customers.debts.pdf');
    Route::get('/debts/{debt}/edit', [DebtController::class, 'edit'])->name('debts.edit');
    Route::put('/debts/{debt}', [DebtController::class, 'update'])->name('debts.update');
    Route::delete('/debts/{debt}', [DebtController::class, 'destroy'])->name('debts.destroy');
    Route::patch('/debts/{debt}/toggle', [DebtController::class, 'togglePaid'])->name('debts.toggle');

    // Route لوصل القبض (Receipts)
    Route::post('/customers/{customer}/receipts', [ReceiptController::class, 'store'])->name('receipts.store');
    Route::get('/receipts/{receipt}/pdf', [ReceiptController::class, 'pdf'])->name('receipts.pdf');
    Route::get('/receipts/{receipt}/print', [ReceiptController::class, 'print'])->name('receipts.print');
    Route::post('/clear-pdf-session', function () {
        session()->forget('download_receipt_pdf');
        return response()->json(['status' => 'ok']);
    });

    //report
    Route::get('/reports/customers', [ReportController::class, 'customers'])->name('reports.customers');
    Route::get('reports/customers/print', [ReportController::class, 'customersPrint'])->name('reports.customers.print');
    Route::get('/reports/customers/pdf', [ReportController::class, 'customersPdf'])->name('reports.customers.pdf');

    //lang
    Route::get('/lang/{lang}', function ($lang) {
        if (in_array($lang, ['ar', 'en'])) {
            session(['locale' => $lang]);
        }
        return back();
    })->name('lang.switch');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
