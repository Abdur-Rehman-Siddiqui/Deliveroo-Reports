<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\endpoints\AccessToken;
use App\Http\Controllers\endpoints\Orders;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('access', [AccessToken::class, 'getAccessToken']);
Route::post('new/order', [Orders::class, 'createNewOrder']);
Route::post('orders', [Orders::class, 'addOrders']);

