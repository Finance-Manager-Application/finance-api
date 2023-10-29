
<?php

use Illuminate\Support\Facades\Route;
use App\Api\Account\AccountController;

Route::get('accounts', [AccountController::class, 'index'])->name('index');
Route::post('accounts', [AccountController::class, 'store'])->name('store');
Route::get('accounts/{user_id}/{account_id}', [AccountController::class, 'show'])->name('show');
Route::put('accounts/{user_id}/{account_id}', [AccountController::class, 'update'])->name('update');
