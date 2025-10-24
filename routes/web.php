<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;

//INDEX
Route::get('/', function () {
    /*if(auth()->check()){
        return redirect()->route('dashboard');
    }else{
        return view('auth.login');
    }*/
    return view('dashboard');
});

Route::get('/stock', function () {
    return view('stock', [
        'products' => Product::getData()
    ]);
});


Route::get('/user', function () {
    return view('user');
});

Route::get('/suppliers', function () {
    return view('suppliers');
});