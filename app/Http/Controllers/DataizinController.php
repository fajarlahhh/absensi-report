<?php

namespace Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Absensi\Izin;
use Absensi\Anggota;
use Illuminate\Support\Facades\Auth;

class DataizinController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth');
    	$this->middleware('permission:data izin');
    }

    public function index(Request $req)
    {
    	$pegawai = null;
    	if ($req->pegawai && $req->pegawai != '00') {
    		$pegawai = $req->pegawai;
    	}
		$tanggal = explode(' - ', $req->get('tgl'));
    	$tgl1 = ($req->get('tgl')? date('Y-m-d', strtotime($tanggal[0])): date('Y-m-1'));
    	$tgl2 = ($req->get('tgl')? date('Y-m-d', strtotime($tanggal[1])): date('Y-m-d'));
    	$kehadiran = Izin::whereBetween("izin_tgl", [$tgl1,$tgl2])->paginate(10);

		$kehadiran->appends($req->tgl1);
		$kehadiran->appends($req->tgl2);
    	return view('pages.absensi.dataizin.index',[
    		'data' => $kehadiran,
            'tgl' => date('d F Y', strtotime($tgl1)).' - '.date('d F Y', strtotime($tgl2))
    	]);
    }

    public function tambah()
	{
    	$anggota = Anggota::all();
		return view('pages.absensi.dataizin.form',[
    		'anggota' => $anggota
		]);
	}

	public function do_tambah(Request $req)
	{
		$req->validate(
			[
				'pegawai_id' => 'required',
				'izin_tgl' => 'required',
				'izin_kode' => 'required',
				'izin_keterangan' => 'required'
			],[
         	   'pegawai_id.required' => 'Anggota tidak boleh kosong',
         	   'izin_tgl.required' => 'Tanggal Izin tidak boleh kosong',
         	   'izin_kode.required' => 'Alasan tidak boleh kosong',
         	   'izin_keterangan.required' => 'Keterangan tidak boleh kosong',
        	]
		);
		try{
			$tanggal = explode(' - ', $req->get('izin_tgl'));
			$diff = date_diff(date_create($tanggal[0]), date_create($tanggal[1]))->format("%a") + 1;
			for ($i=0; $i < $diff; $i++) { 
				$kehadiran = new Izin(); 
				$kehadiran->pegawai_id = $req->get('pegawai_id');
				$kehadiran->izin_tgl = date('Y-m-d', strtotime($tanggal[0]. ' + '.$i.' days'));
				$kehadiran->izin_kode = $req->get('izin_kode');
				$kehadiran->izin_keterangan = $req->get('izin_keterangan');
    			$kehadiran->operator = Auth::user()->pegawai->nm_pegawai;
				$kehadiran->save();
			}
			return redirect($req->get('redirect')? $req->get('redirect'): 'dataizin')
			->with('pesan', 'Berhasil menambah data izin')
			->with('judul', 'Tambah data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect($req->get('redirect')? $req->get('redirect'): 'dataizin')
			->with('pesan', 'Gagal menambah data izin. Error: '.$e->getMessage())
			->with('judul', 'Tambah data')
			->with('tipe', 'error');
		}
	}

	public function hapus($id)
	{
		try{
			Izin::where('izin_id', $id)->delete();
			return redirect()->back()
			->with('pesan', 'Berhasil menghapus data izin (ID:'.$id.')')
			->with('judul', 'Hapus data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect()->back()
			->with('pesan', 'Gagal menghapus data izin (ID:'.$id.') Error: '.$e->getMessage())
			->with('judul', 'Hapus data')
			->with('tipe', 'error');
		}
	}
}
