<?php

use App\Http\Controllers\Api\AccountBookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AccountController;
use App\Http\Middleware\AccountBookInterceptor;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\AccountCategoryController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::prefix('api')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthenticatedSessionController::class, 'create'])
            ->name('login');

        Route::post('login', [AuthenticatedSessionController::class, 'store']);
    });

    Route::middleware('auth')->group(function () {
        Route::get('me', [AuthenticatedSessionController::class, 'me']);
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
            ->name('logout');

        Route::middleware(AccountBookInterceptor::class)->group(function () {
            Route::resource('accounts', AccountController::class);
            Route::get('transactions/get-print-data/{id}', [TransactionController::class, 'getPrintData']);
            Route::get('transactions/get-ledger-data/{account}', [TransactionController::class, 'getLedgerData']);
            Route::get('transactions/get-trial-balance-data', [TransactionController::class, 'getTrialBalanceData']);
            Route::resource('transactions', TransactionController::class);
            Route::get('get-parent-categories', [AccountCategoryController::class, 'getParentCategories']);
            Route::get('get-account-classes', [AccountCategoryController::class, 'getAccountClasses']);
            Route::resource('account-categories', AccountCategoryController::class);
            Route::resource('users', UserController::class);
            Route::resource('account-books', AccountBookController::class);
        });
    });
});


Route::get('/{any}', function () {
    return view('app');
})->where("any", ".*");
