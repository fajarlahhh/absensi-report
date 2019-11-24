<?php

namespace Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Absensi\Kantor;

class DatakantorController extends Controller
{
    public function index(Request $req)
    {
		$lokasi = $req->lokasi? $req->lokasi: 'Kab. Bima';
    	$kantor = Kantor::where('kantor_nama', 'like', '%'.$req->cari.'%')->where('kantor_lokasi', '=', $lokasi)->paginate(10);
		$kantor->appends(['lokasi' => $lokasi, 'cari' => $req->cari])->links();
    	return view('pages.master.datakantor.index',[
    		'data' => $kantor,
    		'lokasi' => $lokasi,
			'cari' => $req->cari
    	]);
    }

    public function tambah()
	{
		return view('pages.master.datakantor.form',[
			'data' => null,
			'aksi' => 'Tambah'
		]);
	}

	public function do_tambah(Request $req)
	{
		$req->validate(
			[
				'kantor_nama' => 'required',
				'kantor_lokasi' => 'required',
			],[
         	   'kantor_nama.required' => 'Nama Kantor tidak boleh kosong',
         	   'kantor_lokasi.required' => 'Lokasi tidak boleh kosong',
        	]
		);
		try{
			$kantor = new Kantor();
			$kantor->kantor_id = $req->get('kantor_id');
			$kantor->kantor_nama = $req->get('kantor_nama');
			$kantor->kantor_lokasi = $req->get('kantor_lokasi');
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
		$kantor = Kantor::find($req->id);
		return view('pages.master.datakantor.form',[
			'data' => $kantor,
			'aksi' => 'Edit'
		]);
	}

	public function do_edit(Request $req)
	{
		$req->validate(
			[
				'kantor_id' => 'required',
				'kantor_nama' => 'required',
				'kantor_lokasi' => 'required',
			],[
         	   'kantor_id.required' => 'ID Kantor tidak boleh kosong',
         	   'kantor_nama.required' => 'Nama Kantor tidak boleh kosong',
         	   'kantor_lokasi.required' => 'Lokasi tidak boleh kosong',
        	]
		);
		try{
			$kantor = new Kantor();
			$kantor->exists = true;
			$kantor->kantor_id = $req->get('kantor_id');
			$kantor->kantor_nama = $req->get('kantor_nama');
			$kantor->kantor_lokasi = $req->get('kantor_lokasi');
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
