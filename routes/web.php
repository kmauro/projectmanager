<?php

use App\Domains\Files\Controllers\FileController;
use App\Domains\Projects\Controllers\ProjectController;
use App\Domains\Users\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('guest')->controller(UserController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::get('/register', 'showSignup')->name('register');

    Route::post('/login', 'login')->middleware('throttle:5,1')->name('login.store');
    Route::post('/register', 'signup')->name('register.store');
});

Route::middleware('auth')->controller(UserController::class)->group(function () {
    Route::get('/profile', 'showProfile')->name('profile');
    Route::get('/users', 'showUsers')->name('users.index');
    Route::get('/users/{user}', 'showUser')->name('users.show');
    Route::get('/settings', 'showSettings')->name('user.settings');
    Route::post('/profile', 'updateProfile')->name('profile.update');
    Route::post('/profile/updatePassword', 'updatePassword')->name('profile.updatePassword');
});


Route::middleware('auth')->controller(ProjectController::class)->group(function (): void {
    Route::get('/projects', 'index')->name('projects.index');
    Route::get('/project/{project}', 'show')->name('projects.show');
    Route::put('/project/{project}', 'update')->name('projects.update');
    Route::delete('/project/{project}', 'destroy')->name('projects.destroy');
});

Route::middleware('auth')->controller(FileController::class)->group(function () {
    Route::get('/file/add', 'showCreate')->name('file.create');
    Route::post('/file/add', 'store')->name('file.store');
});

Route::post('/logout', [UserController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function (): void {
    Route::get('/', function () {
        return Inertia::render('Home', [
            'title' => 'Inicio',
        ]);
    })->name('home');
});
