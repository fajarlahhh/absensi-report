<?php

namespace Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Absensi\Anggota;
use Absensi\Pegawai;
use Absensi\Mesin;
use Illuminate\Support\Facades\DB;

class Dataanggotacontroller extends Controller
{
    //
    public function __construct()
	{
		$this->middleware('auth');
    	$this->middleware('permission:data anggota');
	}

    public function index(Request $req)
    {
    	$anggota = Anggota::join('personalia.pegawai', 'pegawai.id', '=', 'pegawai_id')
		->join('personalia.jabatan', 'pegawai.kd_jabatan', '=', 'jabatan.kd_jabatan')
		->join('personalia.bagian', 'pegawai.kd_bagian', '=', 'bagian.kd_bagian')
		->join('personalia.unit', 'pegawai.kd_unit', '=', 'unit.kd_unit')
		->join('personalia.seksi', 'pegawai.kd_seksi', '=', 'seksi.kd_seksi')
		->where('nm_pegawai', 'like', '%'.$req->cari.'%')
		->orwhere('pegawai_id', 'like', '%'.$req->cari.'%')
		->orwhere('nm_pegawai', 'like', '%'.$req->cari.'%')
		->orwhere('nm_unit', 'like', '%'.$req->cari.'%')
		->orwhere('nm_jabatan', 'like', '%'.$req->cari.'%')
		->orwhere('nm_bagian', 'like', '%'.$req->cari.'%')
		->orwhere('nm_seksi', 'like', '%'.$req->cari.'%')
		->orderBy('nm_pegawai')->paginate(10);
		$anggota->appends($req->only('cari'));
		return view('pages.master.dataanggota.index',[
			'data' => $anggota,
			'cari' => $req->cari
		]);
    }

    public function tambah()
	{
		$pegawai = Pegawai::select('id', 'nm_pegawai')
		->orderBy('nm_pegawai', 'asc')
		->whereNotIn('nip', Anggota::select('anggota_nip')->get())
		->where('kd_status', '!=', '07')
		->get();
		return view('pages.master.dataanggota.form',[
			'data' => null,
			'aksi' => 'Tambah',
			'pegawai' => $pegawai
		]);
	}

	public function do_tambah(Request $req)
	{
		$req->validate(
			[
				'anggota_nip' => 'required',
				'anggota_ip' => 'required|max:15',
				'anggota_key' => 'required',
				'anggota_sn' => 'required'
			],[
         	   'anggota_nip.required' => 'Lokasi tidak boleh kosong',
         	   'anggota_ip.required' => 'IP tidak boleh kosong',
         	   'anggota_ip.max' => 'Kata Sandi max 15 karakter',
         	   'anggota_key.required' => 'Key tidak boleh kosong',
         	   'anggota_sn.required' => 'SN tidak boleh kosong',
        	]
		);
		try{
			if (Anggota::find($req->get('pegawai_id'))) {
				return redirect('dataanggota/tambah')->with('eror', 'Anggota '.$req->get('pegawai_id').' sudah ada');
			}else{
				$anggota = new Anggota();
				$anggota->anggota_nip = $req->get('anggota_nip');
				$anggota->pegawai_id = $req->get('pegawai_id');
				$anggota->save();
			}
			return redirect($req->get('redirect'))
			->with('pesan', 'Berhasil menambah data anggota (NIP:'.$req->get('anggota_nip').')')
			->with('judul', 'Tambah data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect($req->get('redirect'))
			->with('pesan', 'Gagal menambah data anggota (NIP:'.$req->get('anggota_nip').') Error: '.$e)
			->with('judul', 'Tambah data')
			->with('tipe', 'error');
		}
	}

	public function edit($id)
	{
		$anggota = Anggota::find($id);
		return view('pages.master.dataanggota.form',[
			'data' => $anggota,
			'aksi' => 'Edit'
		]);
	}

	public function do_edit(Request $req)
	{
		$req->validate(
			[
				'pegawai_id' => 'required',
				'anggota_hak_akses' => 'required'
			],[
         	   'pegawai_id.required' => 'ID tidak boleh kosong',
         	   'anggota_hak_akses.required' => 'Hak Akses tidak boleh kosong',
        	]
		);
		try{
			$anggota = new Anggota();
			$anggota->exists = true;
			$anggota->pegawai_id = $req->get('pegawai_id');
			$anggota->anggota_hak_akses = $req->get('anggota_hak_akses');
			$anggota->save();
			return redirect($req->get('redirect'))
			->with('pesan', 'Berhasil mengedit data anggota (NIP:'.$req->get('anggota_nip').')')
			->with('judul', 'Edit data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect($req->get('redirect'))
			->with('pesan', 'Gagal mengedit data anggota (NIP:'.$req->get('anggota_nip').') Error: '.$e)
			->with('judul', 'Edit data')
			->with('tipe', 'error');
		}
	}

	public function hapus($id)
	{
		try{
			$data = Anggota::findorfail($id);
			Anggota::destroy($id);
			return redirect()->back()
			->with('pesan', 'Berhasil menghapus data anggota (NIP:'.$data->anggota_nip.')')
			->with('judul', 'Hapus data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect('dataanggota')
			->with('pesan', 'Gagal menghapus data anggota (NIP:'.$req->get('anggota_nip').') Error: '.$e)
			->with('judul', 'Hapus data')
			->with('tipe', 'error');
		}
	}

	public function upload()
	{
		# code...
	}
}
