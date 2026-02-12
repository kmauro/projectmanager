<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;

//INDEX
Route::get('/', function ()
{
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    return view('dashboard');
})->name('dashboard');

Route::get('/login', function ()
{
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
})->name('login');


//USER 
Route::controller(UserController::class)->group(function()
{
    Route::get('/changepassword', 'getChangePassword');
    Route::get('/user/create', 'getAdd');
    Route::get('/logout', 'logout') ->name('logout');
    Route::get('/user/verify-email/{token}', 'verifyEmail');

    Route::post('/login', 'login');
    Route::post('/user/create', 'createUser');
    Route::post('/user/changepassword', 'changePassword');
});


//STOCK
Route::controller(StockController::class)->group(function()
{
    Route::get('/stock', 'show');
    Route::get('/stock/control', 'control');

    Route::post('/stock/control', 'updateStock');
});


//PRODUCTS
Route::controller(ProductController::class)->group(function()
{
    Route::get('/product/add', 'getAdd');
    Route::get('/product/edit/{productId}', 'getUpdate');
    Route::get('/product/delete/{productId}', 'getDelete');
    Route::get('/suppliers/{productId}', 'getProductSuppliers');
    Route::get('/subcategories/{categoryId}', 'getSubcategories');

    Route::post('/product/add', 'addProduct');
    Route::post('/product/edit/{productId}', 'updateProduct');
    Route::post('/product/delete/{productId}', 'deleteProduct');
});


//SUPPLIERS
Route::controller(SupplierController::class)->group(function()
{
    Route::get('/suppliers', 'getSuppliers');
    Route::get('/supplier/add', 'getAdd');
    Route::get('/supplier/edit/{supplierId}', 'getUpdate');
    Route::get('/supplier/delete/{supplierId}', 'getDelete');
    Route::get('/supplier/{supplierId}/products', 'getSupplierProducts');

    Route::post('/supplier/add', 'addSupplier');
    Route::post('/supplier/edit/{supplierId}', 'updateSupplier');
    Route::post('/supplier/delete/{supplierId}', 'deleteSupplier');
});




    