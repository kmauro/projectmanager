<?php

use App\Http\Controllers\FileImportController;
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
    Route::get('/project/view/{id}', 'viewProject')->name('project.view');
    Route::get('/project/edit/{id}', 'editProject')->name('project.edit');
    
    
});

Route::controller(FileImportController::class)->group(function()
{
    Route::get('/files/upload', 'getUploadFileView')->name('files.upload');
    Route::post('/projects/upload', 'uploadFile')->name('projects.upload');
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

