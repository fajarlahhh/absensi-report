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
		'icon' => 'fa fa-cog',
		'title' => 'Setup',
		'url' => 'javascript:;',
		'caret' => true,
		'sub_menu' => [[
			'url' => '/datamesin',
			'title' => 'Data Mesin'
		],[
			'url' => '/datapengguna',
			'title' => 'Data Pengguna'
		]]
	],[
		'icon' => 'fa fa-database',
		'title' => 'Data Master',
		'url' => 'javascript:;',
		'caret' => true,
		'sub_menu' => [[
			'url' => '/dataanggota',
			'title' => 'Data Anggota'
		],[
			'url' => '/datagrup',
			'title' => 'Data Grup'
		]]
	],[
		'icon' => 'fa fa-calendar-alt',
		'title' => 'Absensi',
		'url' => 'javascript:;',
		'caret' => true,
		'sub_menu' => [[
			'url' => '/datakehadiran',
			'title' => 'Data Kehadiran'
		],[
			'url' => '/dataizin',
			'title' => 'Data Izin'
		]]
	]]
];
