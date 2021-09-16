<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/get_all_data_user',[UserController::class,'get_all_data_user']);
Route::post('/delete_data_user',[UserController::class,'delete_data_user']);
Route::post('/save_data_user',[UserController::class,'save_data_user']);
Route::post('/update_data_user',[UserController::class,'update_data_user']);
Route::post('/redirect_payment',[UserController::class,'redirect_payment']);
