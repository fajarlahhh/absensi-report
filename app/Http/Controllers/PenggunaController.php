<?php

namespace Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Absensi\Pengguna;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PenggunaController extends Controller
{
	public function index(Request $req)
	{
		$pengguna = Pengguna::where('pengguna_nama', 'like', '%'.$req->cari.'%')->paginate(10);
		$pengguna->appends($req->only('cari'));
		return view('pages.setup.datapengguna.index',[
			'data' => $pengguna,
			'cari' => $req->cari
		]);
	}

	public function tambah()
	{
		$level = \Spatie\Permission\Models\Role::all();
		$izin = \Spatie\Permission\Models\Permission::all();
		return view('pages.setup.datapengguna.form',[
			'data' => null,
			'izin' => $izin,
			'level' => $level,
			'aksi' => 'Tambah'
		]);
	}

	public function do_tambah(Request $req)
	{
		$req->validate(
			[
				'pengguna_id' => 'required',
				'pengguna_sandi' => 'required|min:5',
				'pengguna_nama' => 'required',
				'pengguna_level' => 'required'
			],[
         	   'pengguna_id.required' => 'ID tidak boleh kosong',
         	   'pengguna_sandi.min' => 'Kata Sandi minimal 5 karakter',
         	   'pengguna_sandi.required'  => 'Kata Sandi tidak boleh kosong',
         	   'pengguna_nama.required'  => 'Nama tidak boleh kosong',
         	   'pengguna_level.required'  => 'Level tidak boleh kosong'
        	]
		);
		try{
			$pengguna = new Pengguna();
			$pengguna->pengguna_id = $req->get('pengguna_id');
			$pengguna->pengguna_nama = $req->get('pengguna_nama');
			$pengguna->pengguna_sandi = Hash::make($req->get('pengguna_sandi'));
			$pengguna->save();
			if(in_array($req->get('pengguna_id'), config('admin.id')))
				$pengguna->assignRole('Administrator');
			else
				$pengguna->assignRole($req->get('pengguna_level'));

			if($req->get('izin')){
				for ($i=0; $i < sizeof($req->get('izin')); $i++) { 
					$pengguna->givePermissionTo($req->get('izin')[$i]);
				}
			}
			return redirect($req->get('redirect')? $req->get('redirect'): 'datapengguna')
			->with('pesan', 'Berhasil menambah data pengguna (ID:'.$req->get('pengguna_id').')')
			->with('judul', 'Tambah data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect($req->get('redirect')? $req->get('redirect'): 'datapengguna')
			->with('pesan', 'Gagal menambah data pengguna (ID:'.$req->get('pengguna_id').') Error: '.$e->getMessage())
			->with('judul', 'Tambah data')
			->with('tipe', 'error');
		}
	}

	public function edit(Request $req)
	{
		$data = Pengguna::findOrFail($req->id);
		$level = (in_array($req->id, config('admin.id'))? \Spatie\Permission\Models\Role::where('name', 'Administrator')->get(): \Spatie\Permission\Models\Role::all());
		$izin = \Spatie\Permission\Models\Permission::all();
		return view('pages.setup.datapengguna.form',[
			'data' => $data,
			'izin' => $izin,
			'level' => $level,
			'aksi' => 'Edit'
		]);
	}

	public function do_edit(Request $req)
	{
		$req->validate(
			[
				'pengguna_id' => 'required',
				'pengguna_nama' => 'required',
				'pengguna_level' => 'required'
			],[
         	   'pengguna_id.required' => 'ID tidak boleh kosong',
         	   'pengguna_nama.required'  => 'Nama tidak boleh kosong',
         	   'pengguna_level.required'  => 'Level tidak boleh kosong'
        	]
		);
		try{
			DB::table('model_has_permissions')->where('pengguna_id', $req->get('id'))->delete();
			$pengguna = Pengguna::findOrFail($req->get('id'));
			$pengguna->exists = true;
			$pengguna->pengguna_id = $req->get('pengguna_id');
			$pengguna->pengguna_nama = $req->get('pengguna_nama');
			if($req->get('pengguna_sandi'))
				$pengguna->pengguna_sandi = Hash::make($req->get('pengguna_sandi'));
			$pengguna->save();
			$pengguna->removeRole($pengguna->getRoleNames()[0]);
			if(in_array($req->get('pengguna_id'), config('admin.id')))
				$pengguna->assignRole('Administrator');
			else
				$pengguna->assignRole($req->get('pengguna_level'));
			if($req->get('izin')){
				for ($i=0; $i < sizeof($req->get('izin')); $i++) { 
					$pengguna->givePermissionTo($req->get('izin')[$i]);
				}
			}
			return redirect($req->get('redirect')? $req->get('redirect'): 'datapengguna')
			->with('pesan', 'Berhasil mengedit data pengguna (ID:'.$req->get('pengguna_id').')')
			->with('judul', 'Edit data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect($req->get('redirect')? $req->get('redirect'): 'datapengguna')
			->with('pesan', 'Gagal mengedit data pengguna (ID:'.$req->get('pengguna_id').') Error: '.$e->getMessage())
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
			$pengguna = Pengguna::findOrFail(Auth::user()->pengguna_id);
			if($pengguna){
				if(!Hash::check($req->get('pengguna_sandi_lama'), $pengguna->pengguna_sandi)){
					return redirect()->back()
					->with('pesan', 'Gagal mengubah kata sandi. Kata sandi lama salah')
					->with('judul', 'Ganti Kata Sandi')
					->with('tipe', 'error');
				}
			}else{
				return redirect()->back()
				->with('pesan', 'Gagal mengubah kata sandi. Data pengguna tidak tersedia')
				->with('judul', 'Ganti Kata Sandi')
				->with('tipe', 'error');
			}
			$pengguna = Pengguna::findOrFail(Auth::user()->pengguna_id);
			$pengguna->exists = true;
			$pengguna->pengguna_sandi = Hash::make($req->get('pengguna_sandi_baru'));
			$pengguna->save();
			return redirect()->back()
			->with('pesan', 'Berhasil mengubah kata sandi')
			->with('judul', 'Ganti Kata Sandi')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect($req->get('redirect')? $req->get('redirect'): 'datapengguna')
			->with('pesan', 'Gagal mengubah kata sandi. Error: '.$e->getMessage())
			->with('judul', 'Ganti Kata Sandi')
			->with('tipe', 'error');
		}
	}

	public function hapus($id)
	{
		try{
			Pengguna::destroy($id);
			return redirect()->back()
			->with('pesan', 'Berhasil menghapus data pengguna (ID:'.$id.')')
			->with('judul', 'Hapus data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect()->back()
			->with('pesan', 'Gagal menghapus data pengguna (ID:'.$id.') Error: '.$e->getMessage())
			->with('judul', 'Hapus data')
			->with('tipe', 'error');
		}
	}
}
