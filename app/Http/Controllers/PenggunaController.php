<?php

namespace Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Absensi\Pengguna;
use Absensi\Pegawai;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PenggunaController extends Controller
{
    //
	public function __construct()
	{
		$this->middleware('auth');
    	$this->middleware('permission:data pengguna');
	}

	public function index(Request $req)
	{
		$pengguna = Pengguna::select('pengguna_nip', 'nm_pegawai', 'nm_unit', 'nm_jabatan', 'nm_bagian', 'nm_seksi')
		->join('personalia.pegawai', 'pegawai.nip', '=', 'pengguna_nip')
		->join('personalia.jabatan', 'pegawai.kd_jabatan', '=', 'jabatan.kd_jabatan')
		->join('personalia.bagian', 'pegawai.kd_bagian', '=', 'bagian.kd_bagian')
		->join('personalia.unit', 'pegawai.kd_unit', '=', 'unit.kd_unit')
		->join('personalia.seksi', 'pegawai.kd_seksi', '=', 'seksi.kd_seksi')
		->where('nm_pegawai', 'like', '%'.$req->cari.'%')
		->orwhere('pengguna_nip', 'like', '%'.$req->cari.'%')
		->orwhere('nm_pegawai', 'like', '%'.$req->cari.'%')
		->orwhere('nm_unit', 'like', '%'.$req->cari.'%')
		->orwhere('nm_jabatan', 'like', '%'.$req->cari.'%')
		->orwhere('nm_bagian', 'like', '%'.$req->cari.'%')
		->orwhere('nm_seksi', 'like', '%'.$req->cari.'%')
		->orderBy('nm_pegawai')->paginate(10);
		$pengguna->appends($req->only('cari'));
		return view('pages.setup.datapengguna.index',[
			'data' => $pengguna,
			'cari' => $req->cari
		]);
	}

	public function tambah()
	{
		$pegawai = Pegawai::select('id', 'nip', 'nm_pegawai')
		->orderBy('nm_pegawai', 'asc')
		->whereNotIn('nip', Pengguna::select('pengguna_nip')->get())
		->where('kd_status', '!=', '07')
		->get();
		$level = \Spatie\Permission\Models\Role::all();
		$izin = \Spatie\Permission\Models\Permission::all();
		return view('pages.setup.datapengguna.form',[
			'data' => null,
			'izin' => $izin,
			'level' => $level,
			'aksi' => 'Tambah',
			'pegawai' => $pegawai
		]);
	}

	public function do_tambah(Request $req)
	{
		$req->validate(
			[
				'pengguna_nip' => 'required',
				'pengguna_sandi' => 'required|min:8',
				'pengguna_hp' => 'required|min:10',
				'pengguna_level' => 'required'
			],[
         	   'pengguna_nip.required' => 'Pegawai tidak boleh kosong',
         	   'pengguna_sandi.min' => 'Kata Sandi minimal 8 karakter',
         	   'pengguna_sandi.required'  => 'Kata Sandi tidak boleh kosong',
         	   'pengguna_hp.min' => 'No. Hp minimal 10 karakter',
         	   'pengguna_hp.required'  => 'No. Hp tidak boleh kosong',
         	   'pengguna_level.required'  => 'Level tidak boleh kosong'
        	]
		);
		try{
			DB::transaction(function () use ($req) {
				if (Pengguna::find($req->get('pengguna_nip'))) {
					return redirect('datapengguna/tambah')->with('eror', 'Pengguna '.$req->get('pengguna_nip').' sudah ada');
				}else{
					$pengguna = new Pengguna();
					$pengguna->pegawai_id = $req->get('pegawai_id');
					$pengguna->pengguna_nip = $req->get('pengguna_nip');
					$pengguna->pengguna_hp = $req->get('pengguna_hp');
					$pengguna->pengguna_sandi = Hash::make($req->get('pengguna_sandi'));
					$pengguna->save();
					if(in_array($req->get('pengguna_nip'), config('admin.nip')))
						$pengguna->assignRole('Administrator');
					else
						$pengguna->assignRole($req->get('pengguna_level'));

					if($req->get('izin')){
						for ($i=0; $i < sizeof($req->get('izin')); $i++) { 
							$pengguna->givePermissionTo($req->get('izin')[$i]);
						}
					}
				}
			});
			return redirect($req->get('redirect')? $req->get('redirect'): 'datapengguna')
			->with('pesan', 'Berhasil menambah data pengguna (nip:'.$req->get('pengguna_nip').')')
			->with('judul', 'Tambah data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect($req->get('redirect')? $req->get('redirect'): 'datapengguna')
			->with('pesan', 'Gagal menambah data pengguna (nip:'.$req->get('pengguna_nip').') Error: '.$e->getMessage())
			->with('judul', 'Tambah data')
			->with('tipe', 'error');
		}
	}

	public function edit(Request $req)
	{
		$pengguna = Pengguna::find($req->id);
		$level = (in_array($req->id, config('admin.nip'))? \Spatie\Permission\Models\Role::where('name', 'Administrator')->get(): \Spatie\Permission\Models\Role::all());
		$izin = \Spatie\Permission\Models\Permission::all();
		return view('pages.setup.datapengguna.form',[
			'data' => $pengguna,
			'izin' => $izin,
			'level' => $level,
			'aksi' => 'Edit'
		]);
	}

	public function do_edit(Request $req)
	{
		$req->validate(
			[
				'pengguna_nip' => 'required',
				'pengguna_nama' => 'required',
				'pengguna_hp' => 'required|min:10',
				'pengguna_level' => 'required'
			],[
         	   'pengguna_nip.required' => 'NIP tidak boleh kosong',
         	   'pengguna_nama.required'  => 'Nama Pegawai tidak boleh kosong',
         	   'pengguna_hp.min' => 'No. Hp minimal 10 karakter',
         	   'pengguna_hp.required'  => 'No. Hp tidak boleh kosong',
         	   'pengguna_level.required'  => 'Level tidak boleh kosong'
        	]
		);
		try{
			DB::transaction(function () use ($req) {
				DB::table('m_izin_model')->where('pengguna_nip', $req->get('pengguna_nip'))->delete();
				$pengguna = new Pengguna();
				$pengguna->exists = true;
				$pengguna->pengguna_nip = $req->get('pengguna_nip');
				$pengguna->pengguna_hp = $req->get('pengguna_hp');
				$pengguna->save();
				$pengguna->removeRole($pengguna->getRoleNames()[0]);
				if(in_array($req->get('pengguna_nip'), config('admin.nip')))
					$pengguna->assignRole('Administrator');
				else
					$pengguna->assignRole($req->get('pengguna_level'));
				if($req->get('izin')){
					for ($i=0; $i < sizeof($req->get('izin')); $i++) { 
						$pengguna->givePermissionTo($req->get('izin')[$i]);
					}
				}
			});
			return redirect($req->get('redirect')? $req->get('redirect'): 'datapengguna')
			->with('pesan', 'Berhasil mengedit data pengguna (nip:'.$req->get('pengguna_nip').')')
			->with('judul', 'Edit data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect($req->get('redirect')? $req->get('redirect'): 'datapengguna')
			->with('pesan', 'Gagal mengedit data pengguna (nip:'.$req->get('pengguna_nip').') Error: '.$e->getMessage())
			->with('judul', 'Edit data')
			->with('tipe', 'error');
		}
	}


	public function sandi(Request $req)
	{
		$req->validate(
			[
				'pengguna_sandi_baru' => 'required',
				'pengguna_sandi_lama' => 'required',
			],[
         	   'pengguna_sandi_lama.required' => 'Sandi Lama tidak boleh kosong',
         	   'pengguna_sandi_baru.required'  => 'Sandi Baru tidak boleh kosong',
        	]
		);
		try{
			$pengguna = Pengguna::find(Auth::user()->pegawai->nip);
			if($pengguna){
				if(!Hash::check($req->get('pengguna_sandi_lama'), $pengguna->pengguna_sandi)){
					return redirect()->back()
					->with('pesan', 'Gagal mengubah kata sandi. Kata sandi lama salah')
					->with('judul', 'Edit data')
					->with('tipe', 'error');
				}
			}else{
				return redirect()->back()
				->with('pesan', 'Gagal mengubah kata sandi. Data pengguna tidak tersedia')
				->with('judul', 'Edit data')
				->with('tipe', 'error');
			}
			$pengguna = new Pengguna();
			$pengguna->exists = true;
			$pengguna->pengguna_nip = Auth::user()->pegawai->nip;
			$pengguna->pengguna_sandi = Hash::make($req->get('pengguna_sandi_baru'));
			$pengguna->save();
			return redirect()->back()
			->with('pesan', 'Berhasil mengubah kata sandi')
			->with('judul', 'Edit data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect($req->get('redirect')? $req->get('redirect'): 'datapengguna')
			->with('pesan', 'Gagal mengubah  kata sandi. Error: '.$e->getMessage())
			->with('judul', 'Edit data')
			->with('tipe', 'error');
		}
	}

	public function hapus($nip)
	{
		try{
			Pengguna::destroy($nip);
			return redirect()->back()
			->with('pesan', 'Berhasil menghapus data pengguna (nip:'.$nip.')')
			->with('judul', 'Hapus data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect()->back()
			->with('pesan', 'Gagal menghapus data pengguna (nip:'.$nip.') Error: '.$e->getMessage())
			->with('judul', 'Hapus data')
			->with('tipe', 'error');
		}
	}
}
