<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Assessment\QuizController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\Training\TrainingCategoryController;
use App\Http\Controllers\Training\TrainingController;
use App\Http\Controllers\Training\TrainingMaterialController;
use App\Http\Controllers\Training\TrainingModuleController;
use App\Http\Controllers\Training\TrainingTypeController;
use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get(
        'dashboard',
        [DashboardController::class, 'index']
    )->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | User Management
    |--------------------------------------------------------------------------
    */

    Route::resource('users', UserController::class)
        ->names('users');

    Route::resource('employees', UserController::class)
        ->names('employees');


    /*
    |--------------------------------------------------------------------------
    | Access Management
    |--------------------------------------------------------------------------
    */

    Route::resource('roles', RoleController::class)
        ->names('roles');

    Route::resource('permissions', UserController::class)
        ->names('permissions');


    /*
    |--------------------------------------------------------------------------
    | Role Permissions
    |--------------------------------------------------------------------------
    */

    Route::get(
        'roles/{role}/permissions',
        [RolePermissionController::class, 'permission']
    )->name('roles.permissions');

    Route::put(
        'roles/{role}/permissions',
        [RolePermissionController::class, 'update']
    )->name('roles.permissions.update');


    /*
    |--------------------------------------------------------------------------
    | Training
    |--------------------------------------------------------------------------
    */

    Route::resource('trainings', TrainingController::class)
        ->names('trainings');

    Route::prefix('training')
        ->name('training.')
        ->group(function () {

            Route::resource(
                'categories',
                TrainingCategoryController::class
            )->names('categories');

            Route::resource(
                'types',
                TrainingTypeController::class
            )->names('types');
        });

    Route::resource('training-modules', TrainingModuleController::class)
        ->names('training-modules');
    Route::prefix('training-modules/{trainingModule}')
        ->name('training-modules.')
        ->group(function () {
            Route::resource('materials', TrainingMaterialController::class)
                ->parameters([
                    'materials' => 'trainingMaterial',
                ]);
        });
});

/*
    |--------------------------------------------------------------------------
    | Assessment
    |--------------------------------------------------------------------------
    */
Route::resource('quizzes', QuizController::class)
    ->parameters(['quizzes' => 'quiz']);


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
