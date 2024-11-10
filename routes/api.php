<?php

use App\Http\Controllers\Api\v1\AccountController;
use App\Http\Controllers\Api\v1\ProductController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'v1'], function() {

    //Users AUTH:
    Route::post('/signup', [AccountController::class, 'signUp']);
    Route::post('/signin', [AccountController::class, 'signIn']);

});


//############-Logged-###########
Route::group(['prefix' => 'v1','middleware' => ['auth:sanctum']], function() {
    //******Admin Control******
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/product/add', [ProductController::class, 'store']);
    Route::get('/product-show/{id}', [ProductController::class, 'show']);
    Route::get('/product-top', [ProductController::class, 'topFive']);
    Route::post('/product-update/{id}', [ProductController::class, 'update']);
    Route::post('/product-delete/{id}', [ProductController::class, 'destroy']);

});
