<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\ProgrammesController;
use App\Http\Controllers\Api\OeuvresController;
use App\Http\Controllers\Api\DemandesController;
use App\Http\Controllers\Api\PieceJointesController;
// use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\TransactionsController;
use App\Http\Controllers\Api\StatisticsController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\NotificationsController;
use App\Http\Controllers\Api\BudgetController;
use App\Http\Controllers\Api\CaisseController;
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
    Route::get('users',[UsersController::class,'getUsers']);
    Route::post('users/create',[UsersController::class,'createUser']);
    Route::post('users/update/{id}',[UsersController::class,'updateUser']);
    Route::post('users/delete/{id}',[UsersController::class,'deleteUser']);
    Route::post('users/changepassword',[UsersController::class,'changePassword']);
    Route::get('users/role',[UsersController::class,'userRole']);
    Route::post('logout',[UsersController::class,'logoutUser']);
    // Programme Management
    Route::get('programmes',[ProgrammesController::class,'getProgrammes']);
    Route::get('programmes-titles',[ProgrammesController::class,'getProgrammesTitles']);
    Route::post('programmes/create',[ProgrammesController::class,'createProgramme']);
    Route::post('programmes/destribute',[ProgrammesController::class,'destributeAmounts']);
    Route::post('programmes/update/{id}',[ProgrammesController::class,'updateProgramme']);
    Route::post('programmes/delete/{id}',[ProgrammesController::class,'deleteProgramme']);
    // Oeuvre Management
    Route::get('oeuvres',[OeuvresController::class,'getOeuvres']);
    Route::post('oeuvres/create',[OeuvresController::class,'createOeuvre']);
    Route::post('oeuvres/update/{id}',[OeuvresController::class,'updateOeuvre']);
    Route::post('oeuvres/delete/{id}',[OeuvresController::class,'deleteOeuvre']);
    // Demande Management
    Route::get('demandes',[DemandesController::class,'getDemandes']);
    Route::get('demandes-by-status',[DemandesController::class,'getDemandesStatus']);
    Route::post('demandes/create',[DemandesController::class,'createDemande']);
    Route::get('demandes/attachments',[PieceJointesController::class,'getAttachments']);
    Route::post('/paydemande/{id}', [DemandesController::class, 'payDemande']);
        // Request File
        Route::get('file',[PieceJointesController::class,'getFile']);
    Route::post('demandes/status/{id}',[DemandesController::class,'changeDemandeStatus']);
    // Statistics
    Route::get('stats',[StatisticsController::class,'getStats']);
    //Dashboard Management 
    Route::get('/programmes/count', [DashboardController::class, 'countProgrammes']);
    Route::get('/annonces/count', [DashboardController::class, 'countAnnonces']);
    Route::get('/demandes/count', [DashboardController::class, 'countDemandes']);
    //Budget Mangement 
    Route::get('/budget',[BudgetController::class,'getBudget']);
    Route::post('/budget',[BudgetController::class,'initBudget']);
    Route::post('/transferer',[ProgrammesController::class,'transferer']);
    Route::post('/distribuer-budget', [TransactionsController::class, 'distribuerBudget']);
    // Route::get('/billan', [TransactionsController::class, 'billan']);
    // Notifications
    Route::post('/notification', [NotificationsController::class, 'push']);
    Route::get('/notification', [NotificationsController::class, 'get']);
    // Route::get('/notifications', [NotificationsController::class, 'getNotificationsForUser']);
    // Route::get('/notificationcount', [NotificationsController::class, 'unreadNotificationsCount']);
    // // Transaction
    Route::get('/transactions',[BudgetController::class,'getTransactions']);
    // Integreation
    Route::post('hey',[UsersController::class,'testLogin']);
    
});

// Login Route
Route::post('admin/setup',[UsersController::class,'setUpAdmin']);
Route::post('caisse/setup',[CaisseController::class,'setUpCaisse']);
   Route::post('login',[UsersController::class,'loginUser']);
// Route::post('/sendresetpasswordemail',[PasswordResetController::class,'sendResetPasswordEmail']);
// Route::post('/resetpassword/{token}', [PasswordResetController::class, 'reset']);


// testing
