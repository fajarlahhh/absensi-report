<?php

namespace Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Absensi\Absen;
use Absensi\Shift;
use Absensi\ShiftKaryawan;
use Absensi\Izin;
use Absensi\Kehadiran;
use Absensi\Aturan;
use Absensi\TglKhusus;
use Absensi\Libur;
use Absensi\Anggota;
use DateTime;
use Illuminate\Support\Facades\Validator;

class PostingabsenController extends Controller
{
    //
    public function __construct()
	{
		$this->middleware('auth');
    	$this->middleware('permission:posting absensi');
	}

	public function index()
	{
		return view('pages.administrator.postingabsensi.index');
	}

	public function posting(Request $req)
	{
		$validator = Validator::make($req->all(),
    		[
    			'tanggal' => 'required'
    		],[
    			'tanggal.required' => 'Tanggal tidak boleh kosong'
    		]);
		if($validator->fails()){
			return \Response::json([
				'pesan' => $validator->errors()->first(),
				'tipe' => 'error'
			]);
    	}
		try{
			ini_set('max_execution_time', 500);
			$tanggal = explode(' - ', $req->get('tanggal'));
			$aturan = Aturan::first();
			Absen::whereBetween("absen_tgl", [date('Y-m-d', strtotime($tanggal[0])),date('Y-m-d', strtotime($tanggal[1]))])->delete();
			$diff = date_diff(date_create($tanggal[0]), date_create($tanggal[1]))->format("%a");
			for ($i=0; $i <= $diff; $i++) {
				$masuk = $aturan->aturan_masuk;
				$pulang = $aturan->aturan_pulang;
				$khusus = null;
				$libur = null;


				$absen_tgl = date('Y-m-d', strtotime($tanggal[0]. ' + '.$i.' days'));
				$absen_hari = 'b';
				$absen_tgl_keterangan = '';
				$absen_masuk_telat = null;

				if(strpos($aturan->aturan_hari_libur, date('N', strtotime($tanggal[0]. ' + '.($i).' days'))) !== false){
					$absen_hari = 'l';
				}else{
					$libur = Libur::where('libur_tgl', date('Y-m-d', strtotime($tanggal[0]. ' + '.$i.' days')))->first();
					if($libur){
						$absen_hari = 'l';
						$absen_tgl_keteranganabsen_tgl_keterangan = $libur->libur_keterangan;
					}else{
						$khusus = TglKhusus::where('tgl_khusus_waktu', date('Y-m-d', strtotime($tanggal[0]. ' + '.$i.' days')))->first();
						if($khusus){
							$masuk = $aturan->aturan_masuk_khusus;
							$pulang = $aturan->aturan_pulang_khusus;
							$absen_hari = 'k';
							$absen_tgl_keterangan = '<b>'.$khusus->tgl_khusus_keterangan.'</b>';
						}
					}
				}

				$anggota = Anggota::select('pegawai_id')->groupBy('pegawai_id')->get();
				foreach ($anggota as $key => $angg) {
					$absen = new Absen(); 

					$absen->absen_tgl = $absen_tgl;
					$absen->absen_hari = $absen_hari;
					$absen->absen_tgl_keterangan = $absen_tgl_keterangan;
					$absen->absen_masuk_telat = $absen_masuk_telat;

					$absen->pegawai_id = $angg->pegawai_id;
					$izin = Izin::where('pegawai_id', $angg->pegawai_id)
									->where('izin_tgl', date('Y-m-d', strtotime($tanggal[0]. ' + '.$i.' days')))
									->first();
					if ($izin) {
						$absen->absen_izin_keterangan = $izin->izin_keterangan;
						switch ($izin->izin_kode) {
							case '11':
								$absen->absen_izin = 'Sakit';
								break;
							case '12':
								$absen->absen_izin = 'Izin';
								break;
							case '13':
								$absen->absen_izin = 'Sakit';
								break;
							case '14':
								$absen->absen_izin = 'Sakit';
								break;
							case '15':
								$absen->absen_izin = 'Sakit';
								break;
							case '16':
								$absen->absen_izin = 'Lain-lain';
								break;
						}
					}
					//cari masuk
					$absen_masuk = Kehadiran::where('pegawai_id', $angg->pegawai_id)
										->whereRaw('date(kehadiran_tgl)="'. date('Y-m-d', strtotime($tanggal[0]. ' + '.$i.' days')).'"')
										->where('kehadiran_kode', 0)
										->orderBy('kehadiran_tgl','asc')
										->first();
					//cari pulang
					$absen_pulang = Kehadiran::where('pegawai_id', $angg->pegawai_id)
										->whereRaw('date(kehadiran_tgl)="'. date('Y-m-d', strtotime($tanggal[0]. ' + '.$i.' days')).'"')
										->where('kehadiran_kode', 1)
										->orderBy('kehadiran_tgl','asc')
										->first();
					//cari keluar
					$absen_istirahat = Kehadiran::where('pegawai_id', $angg->pegawai_id)
										->whereRaw('date(kehadiran_tgl)="'. date('Y-m-d', strtotime($tanggal[0]. ' + '.$i.' days')).'"')
										->where('kehadiran_kode', 2)
										->orderBy('kehadiran_tgl','desc')
										->first();
					//cari datang
					$absen_kembali = Kehadiran::where('pegawai_id', $angg->pegawai_id)
										->whereRaw('date(kehadiran_tgl)="'. date('Y-m-d', strtotime($tanggal[0]. ' + '.$i.' days')).'"')
										->where('kehadiran_kode', 3)
										->orderBy('kehadiran_tgl','asc')
										->first();
					//cari lembur
					$absen_lembur = Kehadiran::where('pegawai_id', $angg->pegawai_id)
										->whereRaw('date(kehadiran_tgl)="'. date('Y-m-d', strtotime($tanggal[0]. ' + '.$i.' days')).'"')
										->where('kehadiran_kode', 4)
										->orderBy('kehadiran_tgl','asc')
										->first();
					//cari pulang lembur
					$absen_pulang_lembur = Kehadiran::where('pegawai_id', $angg->pegawai_id)
										->whereRaw('date(kehadiran_tgl)="'. date('Y-m-d', strtotime($tanggal[0]. ' + '.$i.' days')).'"')
										->where('kehadiran_kode', 5)
										->orderBy('kehadiran_tgl','desc')
										->first();
					$absen->absen_masuk = $absen_masuk? date('H:i:s', strtotime($absen_masuk->kehadiran_tgl)): null;
					$absen->absen_pulang = $absen_pulang? date('H:i:s', strtotime($absen_pulang->kehadiran_tgl)): null;
					$absen->absen_lembur = $absen_lembur? date('H:i:s', strtotime($absen_lembur->kehadiran_tgl)): null;
					$absen->absen_pulang_lembur = $absen_pulang_lembur? date('H:i:s', strtotime($absen_pulang_lembur->kehadiran_tgl)): null;
					$absen->absen_istirahat = $absen_istirahat? date('H:i:s', strtotime($absen_istirahat->kehadiran_tgl)): null;
					$absen->absen_kembali = $absen_kembali? date('H:i:s', strtotime($absen_kembali->kehadiran_tgl)): null;

					$absen->absen_masuk_keterangan = $absen_masuk? $absen_masuk->kehadiran_keterangan: null;
					$absen->absen_pulang_keterangan = $absen_pulang? $absen_pulang->kehadiran_keterangan: null;
					$absen->absen_lembur_keterangan = $absen_lembur? $absen_lembur->kehadiran_keterangan: null;
					$absen->absen_pulang_lembur_keterangan = $absen_pulang_lembur? $absen_pulang_lembur->kehadiran_keterangan: null;
					$absen->absen_istirahat_keterangan = $absen_istirahat? $absen_istirahat->kehadiran_keterangan: null;
					$absen->absen_kembali_keterangan = $absen_kembali? $absen_kembali->kehadiran_keterangan: null;

					if ($absen_masuk) {
						$waktuMasuk = new DateTime($absen_masuk->kehadiran_tgl);
						$shiftkaryawan = ShiftKaryawan::where('pegawai_id', $angg->pegawai_id)->first();
						if ($shiftkaryawan) {
							$aturanMasuk = new DateTime(date('Y-m-d', strtotime($absen_masuk->kehadiran_tgl)).' '.$shiftkaryawan->shift->shift_jam_masuk);
							$absen->absen_masuk_telat = ($waktuMasuk > $aturanMasuk? date_diff($aturanMasuk, $waktuMasuk)->format("%h:%i:%S"): null);
						}else{
							$aturanMasuk = new DateTime(date('Y-m-d', strtotime($absen_masuk->kehadiran_tgl)).' '.$masuk);
							$absen->absen_masuk_telat = ($waktuMasuk > $aturanMasuk? date_diff($aturanMasuk, $waktuMasuk)->format("%h:%i:%S"): null);
						}
					}
					$absen->save();
				}
			}
    	
			return \Response::json([
				'pesan' => 'Proses posting absensi berhasil',
				'tipe' => 'success'
			]);
		} catch (Exception $e) {
			return \Response::json([
				'pesan' => $e->getMessage(),
				'tipe' => 'error'
			]);	
		}
	}
}
