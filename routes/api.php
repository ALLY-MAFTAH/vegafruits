<?php

namespace App\Http\Controllers;
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

Route::get('/new-orders', [OrderController::class, 'getNewOrdersApi']);
Route::match(['PATCH', 'PUT'],'/mark-order-as-paid/{orderId}', [OrderController::class, 'markOrderAsPaid']);
Route::match(['PATCH', 'PUT'],'/mark-order-as-served/{orderId}', [OrderController::class, 'markOrderAsServed']);
Route::match(['PATCH', 'PUT'],'/mark-order-as-contacted/{orderId}', [OrderController::class, 'markOrderAsContacted']);



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
