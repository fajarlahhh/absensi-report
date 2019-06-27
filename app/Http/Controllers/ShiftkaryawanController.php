<?php

namespace Absensi\Http\Controllers;

use Absensi\Anggota;
use Absensi\Shift;
use Absensi\ShiftKaryawan;
use Illuminate\Http\Request;

class ShiftkaryawanController extends Controller
{
    //
    public function __construct()
	{
		$this->middleware('auth');
    	$this->middleware('permission:shift karyawan');
	}

	public function index(Request $req)
	{
		$shift = Shift::all();
		$anggota = Anggota::whereNotIn('shift_id', ShiftKaryawan::select('shift_id')->get())->get();
		
	}
}
