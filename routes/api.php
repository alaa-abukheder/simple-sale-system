<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ClientController;
use App\Http\Controllers\API\SaleController;
use App\Http\Controllers\API\SaleTransactionController;
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
Route::apiResource('products', ProductController::class);
Route::apiResource('clients', ClientController::class);
Route::apiResource('sales', SaleController::class);
Route::get("sales/show/create",[SaleController::class,"showCreate"]);
// Route::apiResource('sale-transactions', SaleTransactionController::class);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
