<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\LoginRegisterController;

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

Route::controller(LoginRegisterController::class)->group(function() {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

// My API documentation link
// https://www.postman.com/solar-eclipse-530889/workspace/animals/collection/21513257-428c8fca-fd4a-4f53-a1ae-f8e38718964b?action=share&creator=21513257


Route::get('/categories',[CategoryController::class,'index']);
Route::get('/categories/{id}',[CategoryController::class,'show']);
Route::get('/products',[ProductController::class,'index']);
Route::get('/products/{id}',[ProductController::class,'show']);

Route::middleware('auth:sanctum')->group( function () {
    Route::post('/logout', [LoginRegisterController::class, 'logout']);
    Route::post('/categories',[CategoryController::class,'store']);
    Route::post('/categories/{id}',[CategoryController::class,'edit']);
    Route::delete('/categories/{id}',[CategoryController::class,'destroy']);

    Route::post('/products',[ProductController::class,'store']);
    Route::post('/products/{id}',[ProductController::class,'edit']);
    Route::delete('/products/{id}',[ProductController::class,'destroy']);

});

