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
		'icon' => 'fa fa-calendar-alt',
		'title' => 'Absensi',
		'url' => 'javascript:;',
		'caret' => true,
		'sub_menu' => [[
			'url' => '/dataizin',
			'title' => 'Data Izin'
		],[
			'url' => '/datakehadiran',
			'title' => 'Data Kehadiran'
		],[
			'url' => '/downloadkehadiran',
			'title' => 'Download Kehadiran'
		]]
	],[
		'icon' => 'fa fa-gavel',
		'title' => 'Administrator',
		'url' => 'javascript:;',
		'caret' => true,
		'sub_menu' => [[
			'url' => '/postingabsensi',
			'title' => 'Posting Absensi'
		],[
			'url' => '/shiftkaryawan',
			'title' => 'Shift Karyawan'
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
			'url' => '/harikhusus',
			'title' => 'Hari Khusus'
		],[
			'url' => '/harilibur',
			'title' => 'Hari Libur'
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
			'url' => '/aturan',
			'title' => 'Aturan'
		],[
			'url' => '/datakantor',
			'title' => 'Data Kantor'
		],[
			'url' => '/datamesin',
			'title' => 'Data Mesin'
		],[
			'url' => '/datapengguna',
			'title' => 'Data Pengguna'
		],[
			'url' => '/jenisizin',
			'title' => 'Jenis Izin'
		],[
			'url' => '/shift',
			'title' => 'Shift'
		]]
	]]
];
