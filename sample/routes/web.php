<?php

/*
 * |--------------------------------------------------------------------------
 * | Web Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register web routes for your application. These
 * | routes are loaded by the RouteServiceProvider within a group which
 * | contains the "web" middleware group. Now create something great!
 * |
 */
Route::get('/', function () {
    return view('welcome');
});

Route::get('/top', 'SampleController@top');

Route::post('/insert', 'SampleController@insert');

Route::post('/update', 'SampleController@update');

Route::post('/delete', 'SampleController@delete');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
