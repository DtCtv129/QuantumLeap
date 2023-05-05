<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ProfileController;

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


Route::group(['prefix' => 'api','middleware' => 'auth:sanctum'],function(){
    Route::post('login',[UserController::class,'loginUser']);
    Route::get('user',[UserController::class,'userDetails']);
    Route::get('logout',[UserController::class,'logout']);
    Route::get('/profile/{id}', [ProfileController::class, 'show']);
    Route::get('/profile/{id}/edit', [ProfileController::class, 'edit']);
    Route::put('/profile/{id}', [ProfileController::class, 'update']);

    Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm']);
    Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm']); 
    Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm']);
    Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm']);

});


Route::group(['prefix' => 'api', 'middleware' => 'auth:sanctum'], function() {
    Route::resource('roles', RoleController::class);
});
