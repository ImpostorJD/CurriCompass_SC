<?php

use App\Http\Controllers\CurriculumController;
use App\Http\Controllers\ProgramsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SchoolYearController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\StudentRecordsController;
use App\Http\Controllers\SubjectsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

//TODO: Add Documentation
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| in all controller, include in fetching the school year table
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

        Route::post('/register', 'register')
            ->middleware('auth.anyrole:Admin');

        Route::get('/logout', 'logout');

        Route::get('/refresh', 'refresh');
        Route::get('/', 'index')
            ->middleware('auth.anyrole:Admin');

        Route::get('/{id}', 'show')
            ->middleware('auth.anyrole:Admin');

        Route::delete('/{id}', 'destroy')
            ->middleware('auth.anyrole:Admin');

        Route::patch('/{id}', 'update')
            ->middleware('auth.anyrole:Admin');
    });

Route::controller(RoleController::class)
    ->prefix('/roles')
    ->group(function (){
        Route::get('/', 'index')
            ->middleware('auth.anyrole:Admin');

        Route::get('/{id}', 'show')
            ->middleware('auth.anyrole:Admin');

        Route::post('/', 'store')
            ->middleware('auth.anyrole:Admin');

        Route::delete('/{id}', 'destroy')
            ->middleware('auth.anyrole:Admin');

        Route::patch('/{id}', 'update')
            ->middleware('auth.anyrole:Admin');
    });

Route::controller(SubjectsController::class)
    ->prefix('/subjects')
    ->group(function (){
        Route::get('/', 'index');

        Route::get('/{id}', 'show');

        Route::post('/', 'store')
            ->middleware('auth.anyrole:Admin, Faculty');

        Route::delete('/{id}', 'destroy')
            ->middleware('auth.anyrole:Admin, Faculty');

        Route::patch('/{id}', 'update')
            ->middleware('auth.anyrole:Admin, Faculty');
    });

Route::controller(ProgramsController::class)
    ->prefix('/programs')
    ->group(function () {
        Route::get('/', 'index');

        Route::get('/{id}', 'show');

        Route::post('/', 'store')
            ->middleware('auth.anyrole:Admin, Faculty');

        Route::delete('/{id}', 'destroy')
            ->middleware('auth.anyrole:Admin, Faculty');

        Route::patch('/{id}', 'update')
            ->middleware('auth.anyrole:Admin, Faculty');
    });

Route::controller(SemesterController::class)
    ->prefix('/semesters')
    ->group(function (){
        Route::get('/', 'index');

        Route::get('/{id}', 'show');

        Route::post('/', 'store')
            ->middleware('auth.anyrole:Admin, Faculty');

        Route::delete('/{id}', 'destroy')
            ->middleware('auth.anyrole:Admin, Faculty');

        Route::patch('/{id}', 'update')
            ->middleware('auth.anyrole:Admin, Faculty');
    });

Route::controller(CurriculumController::class)
    ->prefix('/curriculum')
    ->group(function (){
        Route::get('/', 'index');

        Route::get('/{id}', 'show');

        Route::post('/', 'store')
            ->middleware('auth.anyrole:Admin, Faculty');

        Route::delete('/{id}', 'destroy')
            ->middleware('auth.anyrole:Admin, Faculty');

        Route::patch('/{id}', 'update')
            ->middleware('auth.anyrole:Admin, Faculty');
    });

Route::controller(StudentRecordsController::class)
    ->prefix('/student-records')
    ->group(function (){
        Route::get('/', 'index');

        Route::get('/{id}', 'show');

        Route::post('/', 'store')
            ->middleware('auth.anyrole:Admin, Faculty');

        Route::delete('/{id}', 'destroy')
            ->middleware('auth.anyrole:Admin, Faculty');

        Route::patch('/{id}', 'update')
            ->middleware('auth.anyrole:Admin, Faculty');
    });

Route::controller(SchoolYearController::class)
    ->prefix('/school-year')
    ->group(function (){
        Route::get('/', 'index');

        Route::get('/{id}', 'show');

        Route::post('/', 'store')
            ->middleware('auth.anyrole:Admin, Faculty');

        Route::delete('/{id}', 'destroy')
            ->middleware('auth.anyrole:Admin, Faculty');

        Route::patch('/{id}', 'update')
            ->middleware('auth.anyrole:Admin, Faculty');
    });
