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

Auth::routes();

Route::middleware('auth')->group(function() {
    Route::get('/home', 'HomeController@index')->name('home');

    //tasks
    Route::resource('tasks', 'TaskController');

    //lists
    Route::resource('lists', 'ListController');

    /*Route::name('users.')->group(function() {
        Route::get('', 'UserController@');
    });*/

    //save order
    Route::post('/sort', 'TaskController@saveOrder')->name('sort');
});

