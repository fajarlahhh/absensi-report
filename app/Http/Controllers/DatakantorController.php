<?php

namespace Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Absensi\Kantor;
use Absensi\Unit;
use Illuminate\Support\Facades\DB;

class DatakantorController extends Controller
{
    public function __construct()
	{
		$this->middleware('auth');
    	$this->middleware('permission:data kantor');
	}

    public function index(Request $req)
    {
    	$kantor = Kantor::where('kantor_nama', 'like', '%'.$req->cari.'%')->paginate(10);
		$kantor->appends($req->only('cari'));
    	return view('pages.setup.datakantor.index',[
    		'data' => $kantor,
			'cari' => $req->cari
    	]);
    }

    public function tambah()
	{
		$unit = Unit::all();
		return view('pages.setup.datakantor.form',[
			'data' => null,
			'unit' => $unit,
			'aksi' => 'Tambah'
		]);
	}

	public function do_tambah(Request $req)
	{
		$req->validate(
			[
				'kantor_nama' => 'required|max:15',
				'unit_id' => 'required',
			],[
         	   'kantor_nama.required' => 'Lokasi Kantor tidak boleh kosong',
         	   'kantor_nama.max' => 'Lokasi Kantor max 250 karakter',
         	   'unit_id.required' => 'Unit tidak boleh kosong',
        	]
		);
		try{
			$kantor = new Kantor();
			$kantor->kantor_id = $req->get('kantor_id');
			$kantor->kantor_nama = $req->get('kantor_nama');
			$kantor->unit_id = $req->get('unit_id');
			$kantor->save();
			return redirect($req->get('redirect')? $req->get('redirect'): 'datakantor')
			->with('pesan', 'Berhasil menambah data kantor '.$req->get('kantor_nama').'')
			->with('judul', 'Tambah data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect($req->get('redirect')? $req->get('redirect'): 'datakantor')
			->with('pesan', 'Gagal menambah data kantor '.$req->get('kantor_nama').'. Error: '.$e->getMessage())
			->with('judul', 'Tambah data')
			->with('tipe', 'error');
		}
	}

	public function edit(Request $req)
	{	
		$unit = Unit::all();
		$kantor = Kantor::find($req->id);
		return view('pages.setup.datakantor.form',[
			'data' => $kantor,
			'unit' => $unit,
			'aksi' => 'Edit'
		]);
	}

	public function do_edit(Request $req)
	{
		$req->validate(
			[
				'kantor_id' => 'required',
				'kantor_nama' => 'required|max:15',
				'unit_id' => 'required',
			],[
         	   'kantor_id.required' => 'ID Kantor tidak boleh kosong',
         	   'kantor_nama.required' => 'Lokasi Kantor tidak boleh kosong',
         	   'kantor_nama.max' => 'Lokasi Kantor max 250 karakter',
         	   'unit_id.required' => 'Unit tidak boleh kosong',
        	]
		);
		try{
			$kantor = new Kantor();
			$kantor->exists = true;
			$kantor->kantor_id = $req->get('kantor_id');
			$kantor->kantor_nama = $req->get('kantor_nama');
			$kantor->unit_id = $req->get('unit_id');
			$kantor->save();
			return redirect($req->get('redirect')? $req->get('redirect'): 'datakantor')
			->with('pesan', 'Berhasil mengedit data kantor '.$req->get('kantor_nama').'')
			->with('judul', 'Edit data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect($req->get('redirect')? $req->get('redirect'): 'datakantor')
			->with('pesan', 'Gagal mengedit data kantor '.$req->get('kantor_nama').'. Error: '.$e->getMessage())
			->with('judul', 'Edit data')
			->with('tipe', 'error');
		}
	}

	public function hapus($id)
	{
		try{
			Kantor::destroy($id);
			return redirect()->back()
			->with('pesan', 'Berhasil menghapus data kantor (lokasi:'.$id.')')
			->with('judul', 'Hapus data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect()->back()
			->with('pesan', 'Gagal menghapus data kantor (lokasi:'.$id.') Error: '.$e->getMessage())
			->with('judul', 'Hapus data')
			->with('tipe', 'error');
		}
	}
}
