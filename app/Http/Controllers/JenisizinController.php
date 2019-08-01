<?php

namespace Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Absensi\JenisIzin;
use Absensi\Unit;
use Illuminate\Support\Facades\DB;

class JenisizinController extends Controller
{
    public function index(Request $req)
    {
    	$jenisizin = JenisIzin::where('jenis_izin_keterangan', 'like', '%'.$req->cari.'%')->paginate(10);
		$jenisizin->appends($req->only('cari'));
    	return view('pages.setup.jenisizin.index',[
    		'data' => $jenisizin,
			'cari' => $req->cari
    	]);
    }

    public function tambah()
	{
		return view('pages.setup.jenisizin.form',[
			'data' => null,
			'aksi' => 'Tambah'
		]);
	}

	public function do_tambah(Request $req)
	{
		$req->validate(
			[
				'jenis_izin_keterangan' => 'required',
			],[
         	   'jenis_izin_keterangan.required' => 'Keterangan tidak boleh kosong',
        	]
		);
		try{
			$jenisizin = new JenisIzin();
			$jenisizin->jenis_izin_keterangan = $req->get('jenis_izin_keterangan');
			$jenisizin->persen_transport = str_replace(',', '', $req->get('persen_transport'));
			$jenisizin->persen_kinerja = str_replace(',', '', $req->get('persen_kinerja'));
			$jenisizin->save();
			return redirect($req->get('redirect')? $req->get('redirect'): 'jenisizin')
			->with('pesan', 'Berhasil menambah data Jenis Izin '.$req->get('jenis_izin_keterangan').'')
			->with('judul', 'Tambah data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect($req->get('redirect')? $req->get('redirect'): 'jenisizin')
			->with('pesan', 'Gagal menambah data Jenis Izin '.$req->get('jenis_izin_keterangan').'. Error: '.$e->getMessage())
			->with('judul', 'Tambah data')
			->with('tipe', 'error');
		}
	}

	public function edit(Request $req)
	{	
		$jenisizin = jenisizin::find($req->id);
		return view('pages.setup.jenisizin.form',[
			'data' => $jenisizin,
			'aksi' => 'Edit'
		]);
	}

	public function do_edit(Request $req)
	{
		$req->validate(
			[
				'jenis_izin_keterangan' => 'required',
			],[
         	   'jenis_izin_keterangan.required' => 'Keterangan tidak boleh kosong',
        	]
		);
		try{
			$jenisizin = new JenisIzin();
			$jenisizin->exists = true;
			$jenisizin->jenis_izin_keterangan = $req->get('jenis_izin_keterangan');
			$jenisizin->persen_transport = str_replace(',', '', $req->get('persen_transport'));
			$jenisizin->persen_kinerja = str_replace(',', '', $req->get('persen_kinerja'));
			$jenisizin->save();
			return redirect($req->get('redirect')? $req->get('redirect'): 'jenisizin')
			->with('pesan', 'Berhasil mengedit data Jenis Izin '.$req->get('jenis_izin_keterangan').'')
			->with('judul', 'Edit data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect($req->get('redirect')? $req->get('redirect'): 'jenisizin')
			->with('pesan', 'Gagal mengedit data Jenis Izin '.$req->get('jenis_izin_keterangan').'. Error: '.$e->getMessage())
			->with('judul', 'Edit data')
			->with('tipe', 'error');
		}
	}

	public function hapus($id)
	{
		try{
			JenisIzin::destroy($id);
			return redirect()->back()
			->with('pesan', 'Berhasil menghapus data Jenis Izin'.$id)
			->with('judul', 'Hapus data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect()->back()
			->with('pesan', 'Gagal menghapus data Jenis Izin. Error: '.$e->getMessage())
			->with('judul', 'Hapus data')
			->with('tipe', 'error');
		}
	}
}
