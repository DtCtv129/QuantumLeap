<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\ProgrammesController;
use App\Http\Controllers\Api\OeuvresController;
use App\Http\Controllers\Api\DemandesController;
use App\Http\Controllers\Api\PieceJointesController;
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

Route::group(['middleware' => 'auth:sanctum'], function() {
    // User Management
    Route::post('users/create',[UsersController::class,'createUser']);
    Route::post('users/update/{id}',[UsersController::class,'updateUser']);
    Route::post('users/delete/{id}',[UsersController::class,'deleteUser']);
    Route::post('logout',[UsersController::class,'logoutUser']);
    // Programme Management
    Route::get('programmes',[ProgrammesController::class,'getProgrammes']);
    Route::post('programmes/create',[ProgrammesController::class,'createProgramme']);
    Route::post('programmes/update/{id}',[ProgrammesController::class,'updateProgramme']);
    Route::post('programmes/delete/{id}',[ProgrammesController::class,'deleteProgramme']);
    // Oeuvre Management
    Route::get('oeuvres',[OeuvresController::class,'getOeuvres']);
    Route::post('oeuvres/create',[OeuvresController::class,'createOeuvre']);
    Route::post('oeuvres/update/{id}',[OeuvresController::class,'updateOeuvre']);
    Route::post('oeuvres/delete/{id}',[OeuvresController::class,'deleteOeuvre']);
    // Demande Management
    Route::get('demandes',[DemandesController::class,'getDemandes']);
    Route::post('demandes/create',[DemandesController::class,'createDemande']);
    Route::get('demandes/attachments',[PieceJointesController::class,'getAttachments']);
        // Request File
        Route::get('file',[PieceJointesController::class,'getFile']);
    Route::post('demandes/status/{id}',[DemandesController::class,'changeDemandeStatus']);

});
// Login Route
Route::post('login',[UsersController::class,'loginUser']);

