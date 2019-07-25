<?php

namespace Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Absensi\Mesin;
use Absensi\Kantor;
use Illuminate\Support\Facades\DB;


class DatamesinController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
    	$this->middleware('permission:data mesin');
	}

    public function index(Request $req)
    {
    	$mesin = Mesin::with('kantor')->paginate(10);
		$mesin->appends($req->only('cari'));
    	return view('pages.setup.datamesin.index',[
    		'data' => $mesin,
			'cari' => $req->cari
    	]);
    }

    public function tambah()
	{
		$kantor = Kantor::all();
		return view('pages.setup.datamesin.form',[
			'data' => null,
			'kantor' => $kantor,
			'aksi' => 'Tambah'
		]);
	}

	public function do_tambah(Request $req)
	{
		$req->validate(
			[
				'mesin_lokasi' => 'required',
				'mesin_ip' => 'required|max:15',
				'mesin_key' => 'required',
				'kantor_id' => 'required',
				'mesin_sn' => 'required'
			],[
         	   'mesin_lokasi.required' => 'Lokasi tidak boleh kosong',
         	   'mesin_ip.required' => 'IP tidak boleh kosong',
         	   'mesin_ip.max' => 'Kata Sandi max 15 karakter',
         	   'mesin_key.required' => 'Key tidak boleh kosong',
         	   'kantor_id.required' => 'Kantor tidak boleh kosong',
         	   'mesin_sn.required' => 'SN tidak boleh kosong',
        	]
		);
		try{
			$mesin = new Mesin();
			$mesin->mesin_lokasi = $req->get('mesin_lokasi');
			$mesin->mesin_ip = $req->get('mesin_ip');
			$mesin->mesin_key = $req->get('mesin_key');
			$mesin->kantor_id = $req->get('kantor_id');
			$mesin->mesin_sn = $req->get('mesin_sn');
			$mesin->save();
			return redirect($req->get('redirect')? $req->get('redirect'): 'datamesin')
			->with('pesan', 'Berhasil menambah data mesin (lokasi:'.$req->get('mesin_lokasi').')')
			->with('judul', 'Tambah data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect($req->get('redirect')? $req->get('redirect'): 'datamesin')
			->with('pesan', 'Gagal menambah data mesin (lokasi:'.$req->get('mesin_lokasi').') Error: '.$e->getMessage())
			->with('judul', 'Tambah data')
			->with('tipe', 'error');
		}
	}

	public function edit(Request $req)
	{	
		$kantor = Kantor::all();
		$mesin = Mesin::find($req->id);
		return view('pages.setup.datamesin.form',[
			'data' => $mesin,
			'kantor' => $kantor,
			'aksi' => 'Edit'
		]);
	}

	public function do_edit(Request $req)
	{
		$req->validate(
			[
				'mesin_id' => 'required',
				'mesin_lokasi' => 'required',
				'mesin_ip' => 'required|max:15',
				'mesin_key' => 'required',
				'kantor_id' => 'required',
				'mesin_sn' => 'required'
			],[
         	   'mesin_id.required' => 'ID Mesin tidak boleh kosong',
         	   'mesin_lokasi.required' => 'Lokasi tidak boleh kosong',
         	   'mesin_ip.required' => 'IP tidak boleh kosong',
         	   'mesin_ip.max' => 'Kata Sandi max 15 karakter',
         	   'mesin_key.required' => 'Key tidak boleh kosong',
         	   'kantor_id.required' => 'Kantor tidak boleh kosong',
         	   'mesin_sn.required' => 'SN tidak boleh kosong',
        	]
		);
		try{
			$mesin = new Mesin();
			$mesin->exists = true;
			$mesin->mesin_id = $req->get('mesin_id');
			$mesin->mesin_lokasi = $req->get('mesin_lokasi');
			$mesin->mesin_ip = $req->get('mesin_ip');
			$mesin->mesin_key = $req->get('mesin_key');
			$mesin->mesin_sn = $req->get('mesin_sn');
			$mesin->kantor_id = $req->get('kantor_id');
			$mesin->save();
			return redirect($req->get('redirect')? $req->get('redirect'): 'datamesin')
			->with('pesan', 'Berhasil mengedit data mesin (lokasi:'.$req->get('mesin_lokasi').')')
			->with('judul', 'Edit data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect($req->get('redirect')? $req->get('redirect'): 'datamesin')
			->with('pesan', 'Gagal mengedit data mesin (lokasi:'.$req->get('mesin_lokasi').') Error: '.$e->getMessage())
			->with('judul', 'Edit data')
			->with('tipe', 'error');
		}
	}

	public function hapus($nip)
	{
		try{
			Mesin::destroy($nip);
			return redirect()->back()
			->with('pesan', 'Berhasil menghapus data mesin (lokasi:'.$nip.')')
			->with('judul', 'Hapus data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect()->back()
			->with('pesan', 'Gagal menghapus data mesin (lokasi:'.$nip.') Error: '.$e->getMessage())
			->with('judul', 'Hapus data')
			->with('tipe', 'error');
		}
	}
}
