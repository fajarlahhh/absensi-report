<?php

namespace Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Absensi\Kehadiran;
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
    	$tgl1 = ($req->tgl1? date('Y-m-d', strtotime($req->tgl1)): date('Y-m-1'));
    	$tgl2 = ($req->tgl2? date('Y-m-d', strtotime($req->tgl2)): date('Y-m-d'));
    	$kehadiran = Kehadiran::whereRaw("date(kehadiran_tgl) between '".$tgl1."' and '".$tgl2."'")
    	->whereIn('kehadiran_status', ['I'])->paginate(10);

		$kehadiran->appends($req->tgl1);
		$kehadiran->appends($req->tgl2);
    	return view('pages.absensi.dataizin.index',[
    		'data' => $kehadiran,
    		'tgl1' => $tgl1,
    		'tgl2' => $tgl2
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
				'kehadiran_tgl' => 'required',
				'kehadiran_kode' => 'required',
				'kehadiran_keterangan' => 'required'
			],[
         	   'pegawai_id.required' => 'Anggota tidak boleh kosong',
         	   'kehadiran_tgl.required' => 'Tanggal Izin tidak boleh kosong',
         	   'kehadiran_kode.required' => 'Alasan tidak boleh kosong',
         	   'kehadiran_keterangan.required' => 'Keterangan tidak boleh kosong',
        	]
		);
		try{
			$tanggal = explode(' - ', $req->get('kehadiran_tgl'));
			$diff = date_diff(date_create($tanggal[0]), date_create($tanggal[1]))->format("%a") + 1;
			for ($i=0; $i < $diff; $i++) { 
				$kehadiran = new Kehadiran(); 
				$kehadiran->pegawai_id = $req->get('pegawai_id');
				$kehadiran->kehadiran_tgl = date('Y-m-d 00:00:00', strtotime($tanggal[0]. ' + '.$i.' days'));
				$kehadiran->kehadiran_kode = $req->get('kehadiran_kode');
				$kehadiran->kehadiran_keterangan = $req->get('kehadiran_keterangan');
				$kehadiran->kehadiran_status = 'I';
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
			Kehadiran::destroy($id);
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
