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

Route::group(['middleware' => ['auth']], function () {
	Route::get('/', 'DashboardController@index')->name('dashboard');
	Route::get('/home', 'DashboardController@index');

	Route::group(['middleware' => ['auth', 'permission:data pengguna']], function () {
		Route::get('/datapengguna', 'PenggunaController@index')->name('datapengguna');
		Route::get('/datapengguna/edit', 'PenggunaController@edit')->middleware(['role:administrator|user']);
		Route::get('/datapengguna/tambah', 'PenggunaController@tambah')->middleware(['role:administrator|user']);
		Route::post('/datapengguna/tambah', 'PenggunaController@do_tambah')->middleware(['role:administrator|user']);
		Route::post('/datapengguna/edit', 'PenggunaController@do_edit')->middleware(['role:administrator|user']);
		Route::get('/datapengguna/hapus/{post}', 'PenggunaController@hapus')->middleware(['role:administrator|user']);
	});

	Route::group(['middleware' => ['auth', 'permission:data mesin']], function () {
		Route::get('/datamesin', 'DatamesinController@index')->name('datamesin');
		Route::get('/datamesin/edit', 'DatamesinController@edit')->middleware(['role:administrator|user']);
		Route::get('/datamesin/tambah', 'DatamesinController@tambah')->middleware(['role:administrator|user']);
		Route::post('/datamesin/tambah', 'DatamesinController@do_tambah')->middleware(['role:administrator|user']);
		Route::post('/datamesin/edit', 'DatamesinController@do_edit')->middleware(['role:administrator|user']);
		Route::get('/datamesin/hapus/{post}', 'DatamesinController@hapus')->middleware(['role:administrator|user']);
	});

	Route::group(['middleware' => ['auth', 'permission:data kantor']], function () {
		Route::get('/datakantor', 'DatakantorController@index')->name('datakantor');
		Route::get('/datakantor/edit', 'DatakantorController@edit')->middleware(['role:administrator|user']);
		Route::get('/datakantor/tambah', 'DatakantorController@tambah')->middleware(['role:administrator|user']);
		Route::post('/datakantor/tambah', 'DatakantorController@do_tambah')->middleware(['role:administrator|user']);
		Route::post('/datakantor/edit', 'DatakantorController@do_edit')->middleware(['role:administrator|user']);
		Route::get('/datakantor/hapus/{post}', 'DatakantorController@hapus')->middleware(['role:administrator|user']);
	});

	Route::group(['middleware' => ['auth', 'permission:data anggota']], function () {
		Route::get('/dataanggota', 'Dataanggotacontroller@index')->name('dataanggota');
		Route::get('/dataanggota/edit', 'Dataanggotacontroller@edit')->middleware(['role:administrator|user']);
		Route::get('/dataanggota/tambah', 'Dataanggotacontroller@tambah')->middleware(['role:administrator|user']);
		Route::post('/dataanggota/tambah', 'Dataanggotacontroller@do_tambah')->middleware(['role:administrator|user']);
		Route::post('/dataanggota/edit', 'Dataanggotacontroller@do_edit')->middleware(['role:administrator|user']);
		Route::get('/dataanggota/hapus/{post}', 'Dataanggotacontroller@hapus')->middleware(['role:administrator|user']);
		Route::get('/dataanggota/upload', 'Dataanggotacontroller@upload')->middleware(['role:administrator|user']);
	});
});

Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login')->name('login');

Route::post('/logout', 'Auth\LoginController@logout')->name('logout');