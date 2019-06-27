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
		$data = ShiftKaryawan::where('shift_id', $req->shift? $req->shift: (count($shift) > 0? $shift{0}->shift_id: ''))->get();
		return view('pages.administrator.shiftkaryawan.index', [
			'datashift' => $shift,
			'shift' => $req->shift,
			'data' => $data
		]);
	}

	public function tambah()
	{
		$shift = Shift::all();
		$anggota = Anggota::whereNotIn('anggota_id', ShiftKaryawan::select('anggota_id')->get())->get();
		return view('pages.administrator.shiftkaryawan.form', [
			'shift' => $shift,
			'anggota' => $anggota
		]);
	}

	public function do_tambah(Request $req)
	{
		$req->validate(
			[
				'anggota_id' => 'required',
				'shift_id' => 'required'
			],[
         	   'anggota_id.required' => 'Anggota tidak boleh kosong',
         	   'shift_id.required' => 'Shift tidak boleh kosong'
        	]
		);
		try{
			
			$shiftkaryawan = new ShiftKaryawan();
			$shiftkaryawan->anggota_id = $req->get('anggota_id');
			$shiftkaryawan->shift_id = $req->get('shift_id');
			$shiftkaryawan->save();

			return redirect('shiftkaryawan')
			->with('pesan', 'Berhasil menambah shift karyawan')
			->with('judul', 'Tambah data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect('shiftkaryawan')
			->with('pesan', 'Gagal menambah data shift karyawan.   Error: '.$e->getMessage())
			->with('judul', 'Tambah data')
			->with('tipe', 'error');
		}
	}

	public function hapus($id)
	{
		try{
			$anggota = Anggota::findorfail($id);
			$anggota->delete();
			return redirect()->back()
			->with('pesan', 'Berhasil menghapus data shift karyawan (NIP:'.$anggota->anggota_nip.')')
			->with('judul', 'Hapus data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect()->back()
			->with('pesan', 'Gagal menghapus data shift karyawan (NIP:'.$anggota->anggota_nip.') Error: '.$e->getMessage())
			->with('judul', 'Hapus data')
			->with('tipe', 'error');
		}
	}
}
