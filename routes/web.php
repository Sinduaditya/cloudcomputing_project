<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('payment');
});

Route::get('/youtube', function () {
    return view('index');
});

Route::get('/instagram', function () {
    return view('instagram');
});

Route::get('/tiktok', function () {
    return view('tiktok');
});

Route::get('/short', function () {
    return view('short');
});

Route::get('/reels', function () {
    return view('reels');
});

Route::get('/foto', function () {
    return view('foto');
});

Route::get('/fotott', function () {
    return view('fotott');
});

Route::get('/index', function () {
    return view('fotott');
});

Route::get('/user', function () {
    return view('fotott');
});

Route::get('/payment', function () {
    return view('fotott');
});
