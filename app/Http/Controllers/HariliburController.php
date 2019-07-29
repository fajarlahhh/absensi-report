<?php

namespace Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Absensi\Libur;
use Illuminate\Support\Facades\Auth;

class HariliburController extends Controller
{
    public function index(Request $req){
    	$tgl1 = ($req->tgl1? date('Y-m-d', strtotime($req->tgl1)): date('Y-m-1'));
    	$tgl2 = ($req->tgl2? date('Y-m-d', strtotime($req->tgl2)): date('Y-m-d'));
    	$libur = Libur::whereBetween("libur_tgl", [$tgl1, $tgl2])->where('libur_keterangan', 'like', '%'.$req->cari.'%')->paginate(10);
    	$libur->appends($req->cari);
		$libur->appends($req->tgl1);
		$libur->appends($req->tgl2);
    	return view('pages.master.harilibur.index',[
    		'data' => $libur,
    		'cari' => $req->cari,
    		'tgl1' => $tgl1,
    		'tgl2' => $tgl2
    	]);
    }

    public function tambah()
    {
    	return view('pages.master.harilibur.form');
    }

    public function do_tambah(Request $req){
    	$req->validate(
    		[
    			'libur_tgl' => 'required',
    			'libur_keterangan' => 'required'
    		],[
    			'libur_tgl.required' => 'Tanggal tidak boleh kosong',
    			'libur_keterangan.required' => 'Keterangan tidak boleh kosong'
    		]
    	);

    	try {
    		$libur = new Libur();
    		$libur->libur_tgl = date('Y-m-d', strtotime($req->get('libur_tgl')));
    		$libur->libur_keterangan = $req->get('libur_keterangan');
    		$libur->operator = Auth::user()->pegawai->nm_pegawai;
    		$libur->save();

			return redirect($req->get('redirect')? $req->get('redirect'): 'harilibur')
			->with('pesan', 'Berhasil menambah hari libur')
			->with('judul', 'Tambah data')
			->with('tipe', 'success');
    	} catch (\Exception $e) {
			return redirect($req->get('redirect')? $req->get('redirect'): 'harilibur')
			->with('pesan', 'Gagal menambah hari libur. Error: '.$e->getMessage())
			->with('judul', 'Tambah data')
			->with('tipe', 'error');    		
    	}
    }

	public function hapus($tgl)
	{
		try{
			Libur::destroy(date('Y-m-d', strtotime($tgl)));
			return redirect()->back()
			->with('pesan', 'Berhasil menghapus hari libur (tgl:'.$tgl.')')
			->with('judul', 'Hapus data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect()->back()
			->with('pesan', 'Gagal menghapus hari libur (tgl:'.$tgl.') Error: '.$e->getMessage())
			->with('judul', 'Hapus data')
			->with('tipe', 'error');
		}
	}
}
