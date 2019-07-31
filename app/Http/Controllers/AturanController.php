<?php

namespace Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Absensi\Aturan;
use Illuminate\Support\Facades\Auth;

class AturanController extends Controller
{
    public function index(Request $req)
    {
    	$aturan = Aturan::get();
		$hari = array('Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu');
    	return view('pages.setup.aturan.index',[
    		'data' => $aturan,
    	])->with('hari', $hari);
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
			$data = [];
			for ($i=0; $i < sizeof($req->get('aturan_hari')); $i++) { 
				$data[] =[
					'aturan_hari' => $req->get('aturan_hari')[$i],
					'aturan_kerja' => $req->get('aturan_kerja')[$i],
					'aturan_masuk' => $req->get('aturan_masuk')[$i],
					'aturan_pulang' => $req->get('aturan_pulang')[$i],
					'aturan_masuk_khusus' => $req->get('aturan_masuk_khusus')[$i],
					'aturan_pulang_khusus' => $req->get('aturan_pulang_khusus')[$i],
    				'operator' => Auth::user()->pegawai->nm_pegawai
				];
			}
			Aturan::insert($data);
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
