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
    return view('welcome');
});

Route::get('/youtube', function () {
    return view('youtube');
});

Route::get('/instagram', function () {
    return view('instagram');
});

Route::get('/tiktok', function () {
    return view('tiktok');
});

Route::get('/index', function () {
    return view('index');
});

Route::get('/user', function () {
    return view('user');
});

Route::get('/payment', function () {
    return view('payment');
});

Route::get('/download', function () {
    return view('download');
});

Route::get('/scheduled', function () {
    return view('scheduled');
});

Route::get('/actifity', function () {
    return view('activity');
});

Route::get('/billing', function () {
    return view('billing');
});