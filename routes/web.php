<?php

use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\AppointmentStatusController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/users', [UserController::class, 'index']);
Route::post('/api/users', [UserController::class, 'store']);
Route::put('/api/users/{user}', [UserController::class, 'update']);
Route::delete('/api/users/{user}', [UserController::class, 'destroy']);
Route::patch('api/users/{user}/change-role', [UserController::class, 'changeRole']);
Route::get('/api/users/search', [UserController::class, 'search']);
Route::delete('/api/users', [UserController::class, 'bulkDelete']);


Route::get('/api/appointments', [AppointmentController::class, 'index']);
Route::get('/api/appointment-status', [AppointmentStatusController::class, 'getStatusWithCount']);
Route::post('/api/appointments/create', [AppointmentController::class, 'store']);


Route::get('{view}', ApplicationController::class)->where('view','(.*)');
