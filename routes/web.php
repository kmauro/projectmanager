<?php

use Illuminate\Support\Facades\Route;


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
    return view('stock');
});


Route::get('/user', function () {
    return view('user');
});

Route::get('/suppliers', function () {
    return view('suppliers');
});