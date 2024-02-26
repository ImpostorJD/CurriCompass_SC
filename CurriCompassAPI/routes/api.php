<?php

use App\Http\Controllers\CurriculumController;
use App\Http\Controllers\ProgramsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\StudentRecordsController;
use App\Http\Controllers\SubjectsController;
use App\Http\Controllers\UserController;
use App\Models\Programs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//TODO: Implement Routers [Completed for now, other processes to be followed: DO NOT DELETE]
//TODO: Add Documentation
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

Route::controller(UserController::class)
    ->prefix('/users')
    ->group(function() {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
        Route::get('/logout', 'logout');
        Route::get('/refresh', 'refresh');
        Route::get('/{id}', 'show');
        Route::delete('/{id}', 'destroy');
        Route::patch('/{id}', 'update');
    });
Route::controller(RoleController::class)
    ->prefix('/roles')
    ->group(function (){
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/', 'store');
        Route::delete('/{id}', 'destroy');
        Route::patch('/{id}', 'update');
    });

Route::controller(SubjectsController::class)
    ->prefix('/subjects')
    ->group(function (){
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/', 'store');
        Route::delete('/{id}', 'destroy');
        Route::patch('/{id}', 'update');
    });

Route::controller(ProgramsController::class)
    ->prefix('/programs')
    ->group(function (){
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/', 'store');
        Route::delete('/{id}', 'destroy');
        Route::patch('/{id}', 'update');
    });

Route::controller(SemesterController::class)
    ->prefix('/subjects')
    ->group(function (){
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/', 'store');
        Route::delete('/{id}', 'destroy');
        Route::patch('/{id}', 'update');
    });

Route::controller(CurriculumController::class)
    ->prefix('/subjects')
    ->group(function (){
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/', 'store');
        Route::delete('/{id}', 'destroy');
        Route::patch('/{id}', 'update');
    });

Route::controller(StudentRecordsController::class)
    ->prefix('/subjects')
    ->group(function (){
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/', 'store');
        Route::delete('/{id}', 'destroy');
        Route::patch('/{id}', 'update');
    });
