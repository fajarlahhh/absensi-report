<?php

namespace Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Absensi\Mesin;
use Absensi\Unit;
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
    	$mesin = Mesin::paginate(10);
		$mesin->appends($req->only('cari'));
    	return view('pages.setup.datamesin.index',[
    		'data' => $mesin,
			'cari' => $req->cari
    	]);
    }

    public function tambah()
	{
		$unit = Unit::all();
		return view('pages.setup.datamesin.form',[
			'data' => null,
			'unit' => $unit,
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
				'unit_kd' => 'required',
				'mesin_sn' => 'required'
			],[
         	   'mesin_lokasi.required' => 'Lokasi tidak boleh kosong',
         	   'mesin_ip.required' => 'IP tidak boleh kosong',
         	   'mesin_ip.max' => 'Kata Sandi max 15 karakter',
         	   'mesin_key.required' => 'Key tidak boleh kosong',
         	   'unit_kd.required' => 'Unit tidak boleh kosong',
         	   'mesin_sn.required' => 'SN tidak boleh kosong',
        	]
		);
		try{
			if (Mesin::find($req->get('mesin_lokasi'))) {
				return redirect('datamesin/tambah')->with('eror', 'Mesin '.$req->get('pengguna_nip').' sudah ada');
			}else{
				$mesin = new Mesin();
				$mesin->mesin_lokasi = $req->get('mesin_lokasi');
				$mesin->mesin_ip = $req->get('mesin_ip');
				$mesin->mesin_key = $req->get('mesin_key');
				$mesin->unit_kd = $req->get('unit_kd');
				$mesin->mesin_sn = $req->get('mesin_sn');
				$mesin->save();
			}
			return redirect($req->get('redirect')? $req->get('redirect'): 'datamesin')
			->with('pesan', 'Berhasil menambah data mesin (lokasi:'.$req->get('mesin_lokasi').')')
			->with('judul', 'Tambah data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect($req->get('redirect')? $req->get('redirect'): 'datamesin')
			->with('pesan', 'Gagal menambah data mesin (lokasi:'.$req->get('mesin_lokasi').') Error: '.$e)
			->with('judul', 'Tambah data')
			->with('tipe', 'error');
		}
	}

	public function edit(Request $req)
	{	
		$unit = Unit::all();
		$mesin = Mesin::find($req->id);
		return view('pages.setup.datamesin.form',[
			'data' => $mesin,
			'unit' => $unit,
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
				'unit_kd' => 'required',
				'mesin_sn' => 'required'
			],[
         	   'mesin_id.required' => 'ID Mesin tidak boleh kosong',
         	   'mesin_lokasi.required' => 'Lokasi tidak boleh kosong',
         	   'mesin_ip.required' => 'IP tidak boleh kosong',
         	   'mesin_ip.max' => 'Kata Sandi max 15 karakter',
         	   'mesin_key.required' => 'Key tidak boleh kosong',
         	   'unit_kd.required' => 'Unit tidak boleh kosong',
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
			$mesin->unit_kd = $req->get('unit_kd');
			$mesin->save();
			return redirect($req->get('redirect')? $req->get('redirect'): 'datamesin')
			->with('pesan', 'Berhasil mengedit data mesin (lokasi:'.$req->get('mesin_lokasi').')')
			->with('judul', 'Edit data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect($req->get('redirect')? $req->get('redirect'): 'datamesin')
			->with('pesan', 'Gagal mengedit data mesin (lokasi:'.$req->get('mesin_lokasi').') Error: '.$e)
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
			->with('pesan', 'Gagal menghapus data mesin (lokasi:'.$req->get('mesin_lokasi').') Error: '.$e)
			->with('judul', 'Hapus data')
			->with('tipe', 'error');
		}
	}
}
