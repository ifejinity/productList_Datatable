<?php

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

// product lits
Route::prefix('product')->group(function() {
    Route::get('/', 'Controller@index')->name('product');
    Route::post('/create', 'Controller@create')->name('create');
    Route::get('/delete', 'Controller@delete')->name('delete');
});