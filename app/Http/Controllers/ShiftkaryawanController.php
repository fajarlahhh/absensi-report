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
		$anggota = Anggota::whereNotIn('anggota_id', ShiftKaryawan::select('anggota_id')->get())->get();
		$data = ShiftKaryawan::where('shift_id', $req->shift? $req->shift: $shift{0}->shift_id)->paginate(10);
		$data->appends(['cari' => $req->cari, 'shift' => $req->shift])->links();
		return view('pages.administrator.shiftkaryawan.index', [
			'datashift' => $shift,
			'anggota' => $anggota,
			'cari' => $req->cari,
			'shift' => $req->shift,
			'data' => $data
		]);
	}
}
