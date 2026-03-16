<?php

use App\Http\Controllers\ImportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;


//INDEX
Route::get('/', function ()
{
    /* if (!Auth::check()) {
        return redirect()->route('login');
    } */
    return view('dashboard');
})->name('dashboard');

Route::get('/login', function ()
{
    /* if (Auth::check()) {
        return redirect()->route('dashboard');
    } */
    return view('auth.login');
})->name('login');



Route::controller(ProjectController::class)->group(function()
{
    Route::get('/projects', 'index')->name('projects');
    Route::get('/projects/create', 'create')->name('projects.create');
    
});

Route::controller(ImportController::class)->group(function()
{
    Route::post('/projects/upload', 'upload')->name('projects.upload');
});

//USER 
Route::controller(UserController::class)->group(function()
{
    Route::get('/changepassword', 'getChangePassword');
    Route::get('/user/create', 'getAdd');
    Route::get('/logout', 'getLogout') ->name('logout');
    Route::get('/user/verify-email/{token}', 'verifyEmail');

    Route::post('/login', 'login');
    Route::post('/user/register', 'createUser');
    Route::post('/user/changepassword', 'changePassword');
    Route::post('/user/logout', 'logout');
});

