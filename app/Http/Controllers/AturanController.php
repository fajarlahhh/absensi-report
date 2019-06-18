<?php

namespace Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Absensi\Aturan;

class AturanController extends Controller
{
    //
    public function __construct()
	{
		$this->middleware('auth');
    	$this->middleware('permission:aturan');
	}

    public function index(Request $req)
    {
    	$aturan = Aturan::first();
    	return view('pages.setup.aturan.index',[
    		'data' => $aturan,
    	]);
    }

	public function edit(Request $req)
	{
		$req->validate(
			[
				'aturan_masuk' => 'required',
				'aturan_pulang' => 'required',
				'aturan_masuk_khusus' => 'required',
				'aturan_pulang_khusus' => 'required',
			],[
         	   'aturan_masuk.required' => 'Jam Masuk tidak boleh kosong',
         	   'aturan_pulang.required' => 'Jam Pulang tidak boleh kosong',
         	   'aturan_masuk_khusus.required' => 'Jam Masuk Khusus tidak boleh kosong',
         	   'aturan_pulang_khusus.required' => 'Jam Pulang Khusus tidak boleh kosong'
        	]
		);
		try{
			Aturan::truncate();
			$aturan = new Aturan();
			$aturan->aturan_masuk = $req->get('aturan_masuk');
			$aturan->aturan_pulang = $req->get('aturan_pulang');
			$aturan->aturan_masuk_khusus = $req->get('aturan_masuk_khusus');
			$aturan->aturan_pulang_khusus = $req->get('aturan_pulang_khusus');
			$aturan->aturan_hari_libur = implode($req->get('aturan_hari_libur'), '');
			$aturan->save();
			return redirect($req->get('redirect')? $req->get('redirect'): 'aturan')
			->with('pesan', 'Berhasil mengubah aturan')
			->with('judul', 'Edit data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect($req->get('redirect')? $req->get('redirect'): 'aturan')
			->with('pesan', 'Gagal mengubah aturan. Error: '.$e->getMessage())
			->with('judul', 'Edit data')
			->with('tipe', 'error');
		}
	}
}
