<?php

return [

    /*
    |--------------------------------------------------------------------------
    | View Storage Paths
    |--------------------------------------------------------------------------
    |
    | Most templating systems load templates from disk. Here you may specify
    | an array of paths that should be checked for your views. Of course
    | the usual Laravel view path has already been registered for you.
    |
    */

    'menu' => [[
		'icon' => 'fa fa-th-large',
		'title' => 'Dashboard',
		'url' => '/'
	],[
		'icon' => 'fa fa-database',
		'title' => 'Data Master',
		'url' => 'javascript:;',
		'caret' => true,
		'sub_menu' => [[
			'url' => '/datakantor',
			'title' => 'Data Kantor'
		],[
			'url' => '/datapegawai',
			'title' => 'Data Pegawai'
		]]
	],[
		'icon' => 'fa fa-file-alt',
		'title' => 'Laporan',
		'url' => 'javascript:;',
		'caret' => true,
		'sub_menu' => [[
			'url' => '/rekapabsensi',
			'title' => 'Rekap Absensi'
		],[
			'url' => '/rinciankehadiran',
			'title' => 'Rincian Kehadiran'
		]]
	],[
		'icon' => 'fa fa-cog',
		'title' => 'Setup',
		'url' => 'javascript:;',
		'caret' => true,
		'sub_menu' => [[
			'url' => '/datapengguna',
			'title' => 'Data Pengguna'
		]]
	]]
];
