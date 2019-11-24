<?php

namespace Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Absensi\Pegawai;
use Absensi\Kantor;

class DatapegawaiController extends Controller
{
    public function index(Request $req)
    {
		$kantor = Kantor::all();
		$ktr = $req->kantor? $req->kantor: $kantor{0}->kantor_id;
		$pegawai = Pegawai::where(function($q) use ($req){
			$q->where('pegawai_nama', 'like', '%'.$req->cari.'%');
			$q->orWhere('pegawai_nip', 'like', '%'.$req->cari.'%');
		})->where('kantor_id', $ktr)->paginate(10);
		$pegawai->appends(['kantor' => $ktr, 'cari' => $req->cari])->links();
		return view('pages.master.datapegawai.index',[
			'kantor' => $kantor,
			'data' => $pegawai,
			'ktr' => $ktr,
			'cari' => $req->cari
		]);
    }

    public function tambah()
	{
		$kantor = Kantor::all();
		return view('pages.master.datapegawai.form',[
			'data' => null,
			'kantor' => $katnor,
			'aksi' => 'Tambah'
		]);
	}

	public function do_tambah(Request $req)
	{
		$req->validate(
			[
				'pegawai_nip' => 'required',
				'pegawai_nama' => 'required',
				'pegawai_golongan' => 'required',
				'pegawai_jenis_kelamin' => 'required',
				'kantor_id' => 'required',
			],[
         	   'pegawai_nip.required' => 'NIP tidak boleh kosong',
         	   'pegawai_nama.required' => 'Nama tidak boleh kosong',
         	   'pegawai_golongan.required' => 'Golongan tidak boleh kosong',
         	   'pegawai_jenis_kelamin.required' => 'Jenis kelamin tidak boleh kosong',
         	   'kantor_id.required' => 'Kantor tidak boleh kosong',
        	]
		);
		try{
			$pegawai = new Pegawai();
			$pegawai->pegawai_nip = $req->get('pegawai_nip');
			$pegawai->pegawai_nama = $req->get('pegawai_nama');
			$pegawai->pegawai_golongan = $req->get('pegawai_golongan');
			$pegawai->pegawai_jenis_kelamin = $req->get('pegawai_jenis_kelamin');
			$pegawai->kantor_id = $req->get('kantor_id');
			$pegawai->save();
			$response = [
				'berhasil' => 'berhasil'
			];
	
			return response()->json($response);
		}catch(\Exception $e){
			$response = [
				'status' => $e
			];
	
			return response()->json($response);
		}
	}

	public function hapus($id)
	{
		try{
			Pegawai::destroy($id);
			return redirect()->back()
			->with('pesan', 'Berhasil menghapus data pegawai (NIP:'.$id.')')
			->with('judul', 'Hapus data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect()->back()
			->with('pesan', 'Gagal menghapus data pegawai (NIP:'.$id.') Error: '.$e->getMessage())
			->with('judul', 'Hapus data')
			->with('tipe', 'error');
		}
	}
}
