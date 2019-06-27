<?php

namespace Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Absensi\Shift;
use Illuminate\Support\Facades\DB;

class DatashiftController extends Controller
{
    //
	public function __construct()
	{
		$this->middleware('auth');
    	$this->middleware('permission:shift');
	}

    public function index(Request $req)
    {
    	$shift = Shift::paginate(10);
		$shift->appends($req->only('cari'));
    	return view('pages.setup.datashift.index',[
    		'data' => $shift,
			'cari' => $req->cari
    	]);
    }    

    public function tambah()
	{
		return view('pages.setup.datashift.form',[
			'data' => null,
			'kembali' => url()->previous(),
			'aksi' => 'Tambah'
		]);
	}

	public function do_tambah(Request $req)
	{
		$req->validate(
			[
				'shift_nama' => 'required',
				'shift_jam_masuk' => 'required',
				'shift_jam_pulang' => 'required'
			],[
         	   'shift_nama.required' => 'Nama shift tidak boleh kosong',
         	   'shift_jam_pulang.required' => 'Jam Pulang tidak boleh kosong',
         	   'shift_jam_masuk.required' => 'Jam Masuk tidak boleh kosong'
        	]
		);
		try{
			$shift = new Shift();
			$shift->shift_nama = $req->get('shift_nama');
			$shift->shift_jam_pulang = $req->get('shift_jam_pulang');
			$shift->shift_jam_masuk = $req->get('shift_jam_masuk');
			$shift->save();
			return redirect($req->get('redirect')? $req->get('redirect'): 'shift')
			->with('pesan', 'Berhasil menambah data shift')
			->with('judul', 'Tambah data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect($req->get('redirect')? $req->get('redirect'): 'shift')
			->with('pesan', 'Gagal menambah data shift. Error: '.$e->getMessage())
			->with('judul', 'Tambah data')
			->with('tipe', 'error');
		}
	}

	public function edit(Request $req)
	{
		$shift = Shift::find($req->id);
		return view('pages.setup.datashift.form',[
			'data' => $shift,
			'kembali' => url()->previous(),
			'aksi' => 'Edit'
		]);
	}

	public function do_edit(Request $req)
	{
		$req->validate(
			[
				'shift_nama' => 'required',
				'shift_jam_masuk' => 'required',
				'shift_jam_pulang' => 'required'
			],[
         	   'shift_nama.required' => 'Nama shift tidak boleh kosong',
         	   'shift_jam_pulang.required' => 'Jam Pulang tidak boleh kosong',
         	   'shift_jam_masuk.required' => 'Jam Masuk tidak boleh kosong'
        	]
		);
		try{
			$shift = new Shift();
			$shift->exists = true;
			$shift->shift_id = $req->get('shift_id');
			$shift->shift_nama = $req->get('shift_nama');
			$shift->shift_jam_pulang = $req->get('shift_jam_pulang');
			$shift->shift_jam_masuk = $req->get('shift_jam_masuk');
			$shift->save();
			return redirect($req->get('redirect')? $req->get('redirect'): 'shift')
			->with('pesan', 'Berhasil mengedit data shift')
			->with('judul', 'Edit data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect($req->get('redirect')? $req->get('redirect'): 'shift')
			->with('pesan', 'Gagal mengedit data shift. Error: '.$e->getMessage())
			->with('judul', 'Edit data')
			->with('tipe', 'error');
		}
	}

	public function hapus($id)
	{
		try{
			Shift::destroy($id);
			return redirect()->back()
			->with('pesan', 'Berhasil menghapus data shift')
			->with('judul', 'Hapus data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect()->back()
			->with('pesan', 'Gagal menghapus data shift. Error: '.$e->getMessage())
			->with('judul', 'Hapus data')
			->with('tipe', 'error');
		}
	}
}
