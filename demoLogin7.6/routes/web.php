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
	return view('welcome');
});

Auth::routes([ // 'register' => false
]);

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/search', 'HomeController@search')->name('search');

Route::get('/definition', 'HomeController@definition')->name('def');

// sheet_rows を使用するデータ取得
Route::get('/answer', 'HomeController@getAnswer')->name('answer');

// sheet_datas を使用するデータ取得
Route::get('/answer2', 'HomeController@getAnswer2')->name('answer2');
Route::get('/disp', 'HomeController@disp')->name('disp');

Route::get('/ajax/sample', 'Ajax\ResearchController@sample')->name('sample');
Route::get('/ajax/detail', 'Ajax\ResearchController@sampleDetail')->name('detail');


Route::get('/ajax/data', 'Ajax\ResearchController@getData')->name('data');
Route::get('/ajax/data2', 'Ajax\ResearchController@getData2')->name('data2');
Route::get('/ajax/data3', 'Ajax\ResearchController@getData3')->name('data3');
Route::get('/ajax/cross', 'Ajax\ResearchController@getDetailData')->name('getDetailData');


Route::get('/sales', 'SalesController@index'); // 👈 ブラウザでアクセス
Route::get('/ajax/sales', 'Ajax\SalesController@index'); // 👈 売上データ取得
Route::get('/ajax/sales/years', 'Ajax\SalesController@years'); // 👈 年データ取得（セレクトボックス用）

Route::get('/detail', 'SalesController@detail'); // 👈 ブラウザでアクセス

/**
 * ユーザロール
 */
Route::group([
	'middleware' => [
		'auth',
		'can:user'
	]
], function () {
	// ユーザ一覧
	// Route::get('/account', 'AccountController@index')->name('account.index');
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
	// Route::get('/home', 'HomeController@index')->name('home');
	// // ユーザ登録
	// Route::get('/account/regist', 'AccountController@regist')->name('account.regist');
	// Route::post('/account/regist', 'AccountController@createData')->name('account.regist');

	// // ユーザ編集
	// Route::get('/account/edit/{user_id}', 'AccountController@edit')->name('account.edit');
	// Route::post('/account/edit/{user_id}', 'AccountController@updateData')->name('account.edit');

	// // ユーザ削除
	// Route::post('/account/delete/{user_id}', 'AccountController@deleteData');
});

/**
 * 開発者ロール
 * 緊急メンテや運用で使用するコントローラ等を設定する。
 */
Route::group([
	'middleware' => [
		'auth',
		'can:system-only'
	]
], function () {});