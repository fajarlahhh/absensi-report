<?php

namespace Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Absensi\TglKhusus;
use Illuminate\Support\Facades\Auth;

class HarikhususController extends Controller
{
    public function index(Request $req)
    {
    	$tahun = ($req->tahun? $req->tahun: date('Y'));
    	$libur = TglKhusus::whereRaw("year(tgl_khusus_waktu)='".$tahun."'")->where('tgl_khusus_keterangan', 'like', '%'.$req->cari.'%')->paginate(10);
    	$libur->appends($req->cari);
		$libur->appends($req->tahun);
    	return view('pages.master.harikhusus.index',[
    		'data' => $libur,
    		'cari' => $req->cari,
    		'tahun' => $tahun
    	]);
    }

    public function tambah()
	{
		return view('pages.master.harikhusus.form');
	}

	public function do_tambah(Request $req)
	{
    	$req->validate(
    		[
    			'tgl_khusus_waktu' => 'required',
    			'tgl_khusus_keterangan' => 'required'
    		],[
    			'tgl_khusus_waktu.required' => 'Tanggal tidak boleh kosong',
    			'tgl_khusus_keterangan.required' => 'Keterangan tidak boleh kosong'
    		]
    	);
		try{
			$tanggal = explode(' - ', $req->get('tgl_khusus_waktu'));
			$diff = date_diff(date_create($tanggal[0]), date_create($tanggal[1]))->format("%a");
			for ($i=0; $i < $diff; $i++) { 
				$tglkhusus = new TglKhusus(); 
				$tglkhusus->tgl_khusus_waktu = date('Y-m-d', strtotime($tanggal[0]. ' + '.$i.' days'));
				$tglkhusus->tgl_khusus_keterangan = $req->get('tgl_khusus_keterangan');
    			$tglkhusus->operator = Auth::user()->pegawai->nm_pegawai;
				$tglkhusus->save();
			}
			return redirect($req->get('redirect')? $req->get('redirect'): 'harikhusus')
			->with('pesan', 'Berhasil menambah hari khusus')
			->with('judul', 'Tambah data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect($req->get('redirect')? $req->get('redirect'): 'harikhusus')
			->with('pesan', 'Gagal menambah hari khusus. Error: '.$e->getMessage())
			->with('judul', 'Tambah data')
			->with('tipe', 'error');
		}
	}

	public function hapus($tgl)
	{
		try{
			TglKhusus::destroy(date('Y-m-d', strtotime($tgl)));
			return redirect()->back()
			->with('pesan', 'Berhasil menghapus hari khusus (tgl:'.$tgl.')')
			->with('judul', 'Hapus data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect()->back()
			->with('pesan', 'Gagal menghapus hari khusus (tgl:'.$tgl.') Error: '.$e->getMessage())
			->with('judul', 'Hapus data')
			->with('tipe', 'error');
		}
	}
}
