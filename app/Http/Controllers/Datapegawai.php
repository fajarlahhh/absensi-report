<?php

namespace Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Absensi\Pegawai;
use Absensi\Kantor;

class Datapegawaicontroller extends Controller
{
    public function index(Request $req)
    {
		$kantor = Kantor::all();
		$kantor_id = $req->kantor? $req->kantor: $kantor{0}->kantor_id;
		$pegawai = Pegawai::where(function($q) use ($req){
			$q->where('pegawai_nama', 'like', '%'.$req->cari.'%');
			$q->orWhere('pegawai_nip', 'like', '%'.$req->cari.'%');
		})->where('kantor_id', $kantor_id)->paginate(10);
		$pegawai->appends(['kantor' => $kantor_id, 'cari' => $req->cari])->links();
		return view('pages.master.datapegawai.index',[
			'kantor' => $kantor,
			'kantor_id' => $kantor_id,
			'data' => $pegawai,
			'cari' => $req->cari
		]);
    }

}
