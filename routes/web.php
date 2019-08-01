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

	Route::group(['middleware' => ['role_or_permission:administrator|datakehadiran']], function () {
		Route::get('/datakehadiran', 'DatakehadiranController@index')->name('datakehadiran');
		Route::get('/datakehadiran/tambah', 'DatakehadiranController@tambah')->middleware(['role:administrator|user']);
		Route::post('/datakehadiran/tambah', 'DatakehadiranController@do_tambah')->middleware(['role:administrator|user']);
		Route::get('/datakehadiran/hapus/{post}', 'DatakehadiranController@hapus')->middleware(['role:administrator|user']);
	});

	Route::group(['middleware' => ['role_or_permission:administrator|downloadkehadiran']], function () {
		Route::get('/downloadkehadiran', 'DatakehadiranController@download')->name('downloadkehadiran');
		Route::post('/downloadkehadiran', 'DatakehadiranController@do_download')->middleware(['role:administrator|user']);
	});

	Route::group(['middleware' => ['role_or_permission:administrator|postingabsensi']], function () {
		Route::get('/postingabsensi', 'PostingabsenController@index')->name('postingabsensi');
		Route::post('/postingabsensi', 'PostingabsenController@posting')->middleware(['role:administrator|user']);
	});

	Route::group(['middleware' => ['role_or_permission:administrator|shiftkaryawan']], function () {
		Route::get('/shiftkaryawan', 'ShiftkaryawanController@index')->name('shiftkaryawan');
		Route::get('/shiftkaryawan/tambah', 'ShiftkaryawanController@tambah')->middleware(['role:administrator|user']);
		Route::post('/shiftkaryawan/tambah', 'ShiftkaryawanController@do_tambah')->middleware(['role:administrator|user']);
		Route::get('/shiftkaryawan/hapus/{post}', 'ShiftkaryawanController@hapus')->middleware(['role:administrator|user']);
	});

	Route::group(['middleware' => ['role_or_permission:administrator|harilibur']], function () {
		Route::get('/harilibur', 'HariliburController@index')->name('harilibur');
		Route::get('/harilibur/tambah', 'HariliburController@tambah')->middleware(['role:administrator|user']);
		Route::post('/harilibur/tambah', 'HariliburController@do_tambah')->middleware(['role:administrator|user']);
		Route::get('/harilibur/hapus/{post}', 'HariliburController@hapus')->middleware(['role:administrator|user']);
	});

	Route::group(['middleware' => ['role_or_permission:administrator|jenisizin']], function () {
		Route::get('/jenisizin', 'JenisizinController@index')->name('jenisizin');
		Route::get('/jenisizin/tambah', 'JenisizinController@tambah')->middleware(['role:administrator|user']);
		Route::post('/jenisizin/tambah', 'JenisizinController@do_tambah')->middleware(['role:administrator|user']);
		Route::get('/jenisizin/hapus/{post}', 'JenisizinController@hapus')->middleware(['role:administrator|user']);
	});

	Route::group(['middleware' => ['role_or_permission:administrator|harikhusus']], function () {
		Route::get('/harikhusus', 'HarikhususController@index')->name('harikhusus');
		Route::get('/harikhusus/tambah', 'HarikhususController@tambah')->middleware(['role:administrator|user']);
		Route::post('/harikhusus/tambah', 'HarikhususController@do_tambah')->middleware(['role:administrator|user']);
		Route::get('/harikhusus/hapus/{post}', 'HarikhususController@hapus')->middleware(['role:administrator|user']);
	});

	Route::group(['middleware' => ['role_or_permission:administrator|rinciankehadiran']], function () {
		Route::get('/rinciankehadiran', 'RinciankehadiranController@index')->name('rinciankehadiran');
		Route::get('/rinciankehadiran/pdf', 'RinciankehadiranController@pdf')->name('rinciankehadiran');
	});

	Route::group(['middleware' => ['role_or_permission:administrator|rekapabsensi']], function () {
		Route::get('/rekapabsensi', 'RekapabsensiController@index')->name('rekapabsensi');
	});

	Route::group(['middleware' => ['role_or_permission:administrator|dataizin']], function () {
		Route::get('/dataizin', 'DataizinController@index')->name('dataizin');
		Route::get('/dataizin/tambah', 'DataizinController@tambah')->middleware(['role:administrator|user']);
		Route::get('/dataizin/cetak', 'DataizinController@cetak')->middleware(['role:administrator|user']);
		Route::post('/dataizin/tambah', 'DataizinController@do_tambah')->middleware(['role:administrator|user']);
		Route::get('/dataizin/edit', 'DataizinController@edit')->middleware(['role:administrator|user']);
		Route::post('/dataizin/edit', 'DataizinController@do_edit')->middleware(['role:administrator|user']);
		Route::get('/dataizin/hapus/{post}', 'DataizinController@hapus')->middleware(['role:administrator|user']);
	});

	Route::group(['middleware' => ['role_or_permission:administrator|aturan']], function () {
		Route::get('/aturan', 'AturanController@index')->name('aturan');
		Route::post('/aturan', 'AturanController@edit')->middleware(['role:administrator|user']);
	});

	Route::group(['middleware' => ['role_or_permission:administrator|datapengguna']], function () {
		Route::get('/datapengguna', 'PenggunaController@index')->name('datapengguna');
		Route::get('/datapengguna/edit', 'PenggunaController@edit')->middleware(['role:administrator|user']);
		Route::get('/datapengguna/tambah', 'PenggunaController@tambah')->middleware(['role:administrator|user']);
		Route::post('/datapengguna/tambah', 'PenggunaController@do_tambah')->middleware(['role:administrator|user']);
		Route::post('/datapengguna/edit', 'PenggunaController@do_edit')->middleware(['role:administrator|user']);
		Route::get('/datapengguna/hapus/{post}', 'PenggunaController@hapus')->middleware(['role:administrator|user']);
	});

	Route::group(['middleware' => ['role_or_permission:administrator|datamesin']], function () {
		Route::get('/datamesin', 'DatamesinController@index')->name('datamesin');
		Route::get('/datamesin/edit', 'DatamesinController@edit')->middleware(['role:administrator|user']);
		Route::get('/datamesin/tambah', 'DatamesinController@tambah')->middleware(['role:administrator|user']);
		Route::post('/datamesin/tambah', 'DatamesinController@do_tambah')->middleware(['role:administrator|user']);
		Route::post('/datamesin/edit', 'DatamesinController@do_edit')->middleware(['role:administrator|user']);
		Route::get('/datamesin/hapus/{post}', 'DatamesinController@hapus')->middleware(['role:administrator|user']);
	});


	Route::group(['middleware' => ['role_or_permission:administrator|shift']], function () {
		Route::get('/shift', 'DatashiftController@index')->name('shift');
		Route::get('/shift/edit', 'DatashiftController@edit')->middleware(['role:administrator|user']);
		Route::get('/shift/tambah', 'DatashiftController@tambah')->middleware(['role:administrator|user']);
		Route::post('/shift/tambah', 'DatashiftController@do_tambah')->middleware(['role:administrator|user']);
		Route::post('/shift/edit', 'DatashiftController@do_edit')->middleware(['role:administrator|user']);
		Route::get('/shift/hapus/{post}', 'DatashiftController@hapus')->middleware(['role:administrator|user']);
	});

	Route::group(['middleware' => ['role_or_permission:administrator|datakantor']], function () {
		Route::get('/datakantor', 'DatakantorController@index')->name('datakantor');
		Route::get('/datakantor/edit', 'DatakantorController@edit')->middleware(['role:administrator|user']);
		Route::get('/datakantor/tambah', 'DatakantorController@tambah')->middleware(['role:administrator|user']);
		Route::post('/datakantor/tambah', 'DatakantorController@do_tambah')->middleware(['role:administrator|user']);
		Route::post('/datakantor/edit', 'DatakantorController@do_edit')->middleware(['role:administrator|user']);
		Route::get('/datakantor/hapus/{post}', 'DatakantorController@hapus')->middleware(['role:administrator|user']);
	});

	Route::group(['middleware' => ['role_or_permission:administrator|dataanggota']], function () {
		Route::get('/dataanggota', 'Dataanggotacontroller@index')->name('dataanggota');
		Route::get('/dataanggota/tambah', 'Dataanggotacontroller@tambah')->middleware(['role:administrator|user']);
		Route::post('/dataanggota/tambah', 'Dataanggotacontroller@do_tambah')->middleware(['role:administrator|user']);
		Route::post('/dataanggota/download', 'Dataanggotacontroller@download')->middleware(['role:administrator|user']);
		Route::post('/dataanggota/upload', 'Dataanggotacontroller@upload')->middleware(['role:administrator|user']);
		Route::get('/dataanggota/hapus/{post}', 'Dataanggotacontroller@hapus')->middleware(['role:administrator|user']);
		Route::get('/dataanggota/uploadanggota', 'Dataanggotacontroller@upload_anggota')->middleware(['role:administrator|user']);
		Route::post('/dataanggota/uploadanggota', 'Dataanggotacontroller@do_upload_anggota')->middleware(['role:administrator|user']);
	});
});

Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login')->name('login');

Route::post('/logout', 'Auth\LoginController@logout')->name('logout');