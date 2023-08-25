<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Api\Authentication\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => '\App\Api', 'prefix' => '/v1'], function () {

    foreach (scandir($path = app_path('Api')) as $dirName) {
        if ($dirName !== '.' && $dirName !== '..') {
            $routesFile = $path . '/' . $dirName . '/routes.php';
            if (file_exists($routesFile)) {
                include $routesFile;
            }
        }
    }
});

