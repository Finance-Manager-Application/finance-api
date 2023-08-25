
<?php

use Illuminate\Support\Facades\Route;
use App\Api\Authentication\UserController;

Route::post('users/login', [UserController::class, 'login'])->name('login');
Route::post('users/register', [UserController::class, 'register'])->name('register');

