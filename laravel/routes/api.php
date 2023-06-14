<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MessagesController;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => 'auth:api'], function (Registrar $router) {
    $router->group(['prefix' => 'auth'], function (Registrar $router) {
        $router->post('logout', [AuthController::class, 'logout']);
        $router->post('me', [AuthController::class, 'me']);
    });

    $router->get('messages', MessagesController::class);
});
