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
Route::get('/absensikaryawan', 'RinciankehadiranController@tampil')->name('absensikaryawan');

Route::group(['middleware' => ['auth']], function () {
	Route::get('/', 'DashboardController@index')->name('dashboard');
	Route::get('/home', 'DashboardController@index');
	Route::post('/gantisandi', 'PenggunaController@sandi');

	Route::group(['middleware' => ['role_or_permission:administrator|rinciankehadiran']], function () {
		Route::get('/rinciankehadiran', 'RinciankehadiranController@index')->name('rinciankehadiran');
		Route::get('/rinciankehadiran/pdf', 'RinciankehadiranController@pdf')->name('rinciankehadiran');
	});

	Route::group(['middleware' => ['role_or_permission:administrator|rekapabsensi']], function () {
		Route::get('/rekapabsensi', 'RekapabsensiController@index')->name('rekapabsensi');
		Route::get('/rekapabsensi/pdf', 'RekapabsensiController@pdf')->name('rekapabsensi');
	});

	Route::group(['middleware' => ['role_or_permission:administrator|datapengguna']], function () {
		Route::get('/datapengguna', 'PenggunaController@index')->name('datapengguna');
		Route::get('/datapengguna/edit', 'PenggunaController@edit')->middleware(['role:administrator|user']);
		Route::get('/datapengguna/tambah', 'PenggunaController@tambah')->middleware(['role:administrator|user']);
		Route::post('/datapengguna/tambah', 'PenggunaController@do_tambah')->middleware(['role:administrator|user']);
		Route::post('/datapengguna/edit', 'PenggunaController@do_edit')->middleware(['role:administrator|user']);
		Route::get('/datapengguna/hapus/{post}', 'PenggunaController@hapus')->middleware(['role:administrator|user']);
	});

	Route::group(['middleware' => ['role_or_permission:administrator|datakantor']], function () {
		Route::get('/datakantor', 'DatakantorController@index')->name('datakantor');
		Route::get('/datakantor/edit', 'DatakantorController@edit')->middleware(['role:administrator|user']);
		Route::get('/datakantor/tambah', 'DatakantorController@tambah')->middleware(['role:administrator|user']);
		Route::post('/datakantor/tambah', 'DatakantorController@do_tambah')->middleware(['role:administrator|user']);
		Route::post('/datakantor/edit', 'DatakantorController@do_edit')->middleware(['role:administrator|user']);
		Route::get('/datakantor/hapus/{post}', 'DatakantorController@hapus')->middleware(['role:administrator|user']);
	});

	Route::group(['middleware' => ['role_or_permission:administrator|datapegawai']], function () {
		Route::get('/datapegawai', 'DatapegawaiController@index')->name('datapegawai');
	});
});

Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login')->name('login');

Route::post('/logout', 'Auth\LoginController@logout')->name('logout');