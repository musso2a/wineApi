<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WineController;
use Illuminate\Support\Facades\Route;

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


Route::get('mock/user', [AuthController::class, 'mockUser']);
Route::get('mock/wine', [AuthController::class, 'mockWine']);

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::apiResource('/users', UserController::class)->except(['update']);
    Route::post('/users/{user}', [UserController::class, 'update']);
    Route::apiResource('/wines', WineController::class)->except(['update']);
    Route::post('/wines/{wine}', [WineController::class, 'update']);
    // User Wines
    Route::get('/users/{user}/wines', [UserController::class, 'usersWine',]);
    Route::post('/users/{user}/wines', [UserController::class, 'usersWineStore']);
    
});

Route::fallback(function () {
    return response()->json(['message' => 'Whoops..'], 404);
});
