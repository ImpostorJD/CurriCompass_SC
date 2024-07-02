<?php

use App\Http\Controllers\CourseAvailabilityController;
use App\Http\Controllers\CurriculumController;
use App\Http\Controllers\EnlistmentController;
use App\Http\Controllers\ProgramsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SchoolYearController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\SemSyController;
use App\Http\Controllers\StudentRecordsController;
use App\Http\Controllers\SubjectsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\YearLevelController;
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
Route::controller(YearLevelController::class)
    ->prefix('/year-level')
    ->group(function(){
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
    });

Route::controller(UserController::class)
    ->prefix('/users')
    ->group(function() {
        Route::post('/login', 'login');

        Route::post('/change-password/{id}', 'change_password');

        Route::post('/register', 'register')
            ->middleware('auth.anyrole:Admin');

        Route::get('/logout', 'logout');
        Route::get('/profile', 'profile');

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

Route::controller(CourseAvailabilityController::class)
    ->prefix('course-availability')
    ->group(function(){
    Route::get('/', 'index')
        ->middleware('auth.anyrole:Admin,Staff');

    Route::get('/student-subject/{id}', 'latestAvailabilityForStudent')
        ->middleware('auth.anyrole:Admin,Staff');

    Route::get('/{id}', 'show')
        ->middleware('auth.anyrole:Admin,,Staff');

    Route::post('/', 'store')
        ->middleware('auth.anyrole:Admin,Staff');

    Route::delete('/{id}', 'destroy')
        ->middleware('auth.anyrole:Admin,Staff');

    Route::patch('/{id}', 'update')
        ->middleware('auth.anyrole:Admin,Staff');
    });

Route::controller(SubjectsController::class)
    ->prefix('/subjects')
    ->group(function (){
        Route::get('/', 'index');

        Route::get('/{id}', 'show');

        Route::post('/', 'store')
            ->middleware('auth.anyrole:Admin,Staff');

        Route::delete('/{id}', 'destroy')
            ->middleware('auth.anyrole:Admin,Staff');

        Route::patch('/{id}', 'update')
            ->middleware('auth.anyrole:Admin,Staff');
    });


Route::controller(ProgramsController::class)
    ->prefix('/programs')
    ->group(function () {
        Route::get('/', 'index');

        Route::get('/{id}', 'show');

        Route::post('/', 'store')
            ->middleware('auth.anyrole:Admin,Staff');

        Route::delete('/{id}', 'destroy')
            ->middleware('auth.anyrole:Admin,Staff');

        Route::patch('/{id}', 'update')
            ->middleware('auth.anyrole:Admin,Staff');
    });

Route::controller(SemesterController::class)
    ->prefix('/semesters')
    ->group(function (){
        Route::get('/', 'index');

        Route::get('/{id}', 'show');

        Route::post('/', 'store')
            ->middleware('auth.anyrole:Admin,Staff');

        Route::delete('/{id}', 'destroy')
            ->middleware('auth.anyrole:Admin,Staff');

        Route::patch('/{id}', 'update')
            ->middleware('auth.anyrole:Admin,Staff');
    });

Route::controller(SemSyController::class)
    ->prefix('/semester-management')
    ->group(function (){
        Route::get('/', 'index')
            ->middleware('auth.anyrole:Admin,Staff');

        Route::get('/current', 'latest');

        Route::get('/{id}', 'show')
        ->middleware('auth.anyrole:Admin,Staff');

        Route::post('/', 'store')
            ->middleware('auth.anyrole:Admin,Staff');

        Route::post('/generate-latest', 'generateLatest')
            ->middleware('auth.anyrole:Admin,Staff');

        Route::delete('/{id}', 'destroy')
            ->middleware('auth.anyrole:Admin,Staff');

        Route::patch('/{id}', 'update')
            ->middleware('auth.anyrole:Admin,Staff');
    });

Route::controller(CurriculumController::class)
    ->prefix('/curriculum')
    ->group(function (){
        Route::get('/', 'index');

        Route::get('/{id}', 'show');

        Route::post('/', 'store')
            ->middleware('auth.anyrole:Admin,Staff');

        Route::delete('/{id}', 'destroy')
            ->middleware('auth.anyrole:Admin,Staff');

        Route::patch('/{id}', 'update')
            ->middleware('auth.anyrole:Admin,Staff');
    });

Route::controller(StudentRecordsController::class)
    ->prefix('/student-records')
    ->group(function (){
        Route::get('/', 'index')
            ->middleware('auth.anyrole:Admin,Staff');

        Route::get('/{id}', 'show');

        Route::post('/', 'store')
            ->middleware('auth.anyrole:Admin,Staff');

        Route::delete('/{id}', 'destroy')
            ->middleware('auth.anyrole:Admin,Staff');

        Route::patch('/{id}', 'update')
            ->middleware('auth.anyrole:Admin,Staff');
    });

Route::controller(SchoolYearController::class)
    ->prefix('/school-year')
    ->group(function (){
        Route::get('/', 'index');

        Route::get('/{id}', 'show');

        Route::post('/', 'store')
            ->middleware('auth.anyrole:Admin');

        Route::delete('/{id}', 'destroy')
            ->middleware('auth.anyrole:Admin');

        Route::patch('/{id}', 'update')
            ->middleware('auth.anyrole:Admin');
    });

Route::controller(EnlistmentController::class)
 ->prefix('/enlistment')
 ->group(function (){
    Route::get('/', 'index')
    ->middleware('auth.anyrole:Admin,Staff');

    Route::get('/{id}', 'show');

    Route::post('/', 'store');

    Route::patch('/{id}', 'update')
        ->middleware('auth.anyrole:Admin,Staff');

    Route::delete('/{id}', 'destroy')
        ->middleware('auth.anyrole:Admin,Staff');

 });
