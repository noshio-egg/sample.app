<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
	return redirect()->to('/home');
});

// Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');

Auth::routes([
	'register' => false
]);

/**
 * ユーザロール
 */
Route::group([
	'middleware' => [
		'auth',
		'can:user'
	]
], function () {
	/**
	 * ホーム
	 */
	Route::get('/home', 'HomeController@index')->name('home');

	/**
	 * アンケート定義一覧
	 */
	Route::get('/sheet/list', 'SheetController@sheets')->name('sheets');
});

/**
 * 管理者ロール
 */
Route::group([
	'middleware' => [
		'auth',
		'can:admin'
	]
], function () {
	/**
	 * アンケート内容取得
	 */
	Route::get('/sheet/def', 'SheetController@definition')->name('def');

	/**
	 * アンケート回答取得
	 */
	Route::get('/sheet/data', 'SheetController@data')->name('data');

	/**
	 * アンケート回答一覧表示
	 */
	Route::get('/sheet/answer/list', 'SheetController@answers')->name('answerlist');
});

/**
 * 開発者ロール
 * 緊急メンテや運用で使用するコントローラ等を設定する。
 */
Route::group([
	'middleware' => [
		'auth',
		'can:developer'
	]
], function () {});
