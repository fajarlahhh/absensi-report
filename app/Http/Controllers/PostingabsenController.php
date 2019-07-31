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
			$tanggal = explode(' - ', $req->get('tanggal'));
			$dataaturan = Aturan::get();
			$tgl1 = date('Y-m-d', strtotime($tanggal[0]));
			$tgl2 = date('Y-m-d', strtotime($tanggal[1]));

			Absen::whereBetween("absen_tgl", [date('Y-m-d', strtotime($tanggal[0])),date('Y-m-d', strtotime($tanggal[1]))])->delete();
			$diff = date_diff(date_create($tanggal[0]), date_create($tanggal[1]))->format("%a");
			$absensi = Anggota::with('shiftKaryawan.shift')->with(['izin' => function($q) use ($tgl1, $tgl2){
				$q->whereBetween('izin_tgl', [$tgl1, $tgl2]);
			}])->with(['kehadiran' => function($q) use ($tgl1, $tgl2){
				$q->whereRaw('date(kehadiran_tgl) between "'.$tgl1.'" and "'.$tgl2.'"');
			}])->select('pegawai_id')->groupBy('pegawai_id')->get();
			$khusus = TglKhusus::whereBetween('tgl_khusus_waktu', [$tgl1, $tgl2])->get();
			$libur = Libur::whereBetween('libur_tgl', [$tgl1, $tgl2])->get();
			$data = [];
			foreach ($absensi as $key => $abs) {
				$shift_karyawan = null;
				if(sizeof($abs->shiftKaryawan) > 0)
					$shift_karyawan = $abs->shiftKaryawan[0]->shift;

				for ($i=0; $i <= $diff; $i++) {
					$pegawai_id = $abs->pegawai_id;
					$tgli = date('Y-m-d', strtotime($tgl1. ' + '.$i.' days'));
					$hari = date('N', strtotime($tgl1. ' + '.$i.' days'));
					$absen_tgl =$tgli;

					$aturan = $dataaturan->first(function($q) use($hari){
						return $q->aturan_hari == $hari;
					});

					$masuk = $aturan->aturan_masuk;
					$pulang = $aturan->aturan_pulang;
					if ($khusus) {
						$khusus = $khusus->first(function($tgl) use ($tgli){
							return $tgl->tgl_khusus_waktu == $tgli;
						});
					}else{
						$khusus = null;
					}
					if ($libur) {
						$libur = $libur->first(function($tgl) use ($tgli){
							return $tgl->libur_tgl == $tgli;
						});
					}else{
						$khusus = null;
					}

					$absen_hari = 'b';
					$absen_tgl_keterangan = '';
					$absen_masuk_telat = null;
					if ($shift_karyawan) {
						$absen_shift = 1;
						$masuk = $shift_karyawan->shift_jam_masuk;
						$pulang = $shift_karyawan->shift_jam_pulang;
						if($khusus){
							$masuk = $shift_karyawan->shift_jam_masuk_khusus;
							$pulang = $shift_karyawan->shift_jam_pulang_khusus;
							$absen_hari = 'k';
							$absen_tgl_keterangan = '<b>'.$khusus->tgl_khusus_keterangan.'</b>';
						}
					}else{
						$absen_shift = 0;
						if($aturan->aturan_kerja == 'Libur'){
							$absen_hari = 'l';
						}else{
							if($libur){
								$absen_hari = 'l';
								$absen_tgl_keteranganabsen_tgl_keterangan = $libur->libur_keterangan;
							}else{
								if($khusus){
									$masuk = $aturan->aturan_masuk_khusus;
									$pulang = $aturan->aturan_pulang_khusus;
									$absen_hari = 'k';
									$absen_tgl_keterangan = '<b>'.$khusus->tgl_khusus_keterangan.'</b>';
								}
							}
						}
					}


					$absen_masuk = null;
					$absen_masuk_keterangan = null;
					$absen_pulang = null;
					$absen_pulang_keterangan = null;
					$absen_lembur = null;
					$absen_lembur_keterangan = null;
					$absen_pulang_lembur = null;
					$absen_pulang_lembur_keterangan = null;
					$absen_istirahat = null;
					$absen_istirahat_keterangan = null;
					$absen_kembali = null;
					$absen_kembali_keterangan = null;
					$absen_izin = null;
					$absen_izin_keterangan = null;
					$izin = $abs->izin->first(function($tgl) use ($tgli){
						return substr($tgl->izin_tgl, 0, 10) == $tgli;
					});
					if($izin){
						switch ($izin->izin_kode) {
							case '11':
								$absen_izin = 'Sakit';
								break;
							case '12':
								$absen_izin = 'Izin';
								break;
							case '13':
								$absen_izin = 'Dispensasi';
								break;
							case '14':
								$absen_izin = 'Tugas Dinas';
								break;
							case '15':
								$absen_izin = 'Cuti';
								break;
							case '16':
								$absen_izin = 'Lain-lain';
								break;
						}
					}else{
						$data_masuk = $abs->kehadiran->first(function($kode) use ($tgli){
							return $kode->kehadiran_kode == "0";
						});
						if($data_masuk)
							$data_masuk = $abs->kehadiran->first(function($tgl) use ($tgli){
								return substr($tgl->kehadiran_tgl, 0, 10) == $tgli;
							});
						$absen_masuk = $data_masuk? date('H:i:s', strtotime($data_masuk->kehadiran_tgl)): null;
						$absen_masuk_keterangan = $data_masuk? $data_masuk->kehadiran_keterangan: null;
						if ($absen_masuk) {
							$waktuMasuk = new DateTime($data_masuk->kehadiran_tgl);
							$aturanMasuk = new DateTime(date('Y-m-d', strtotime($data_masuk->kehadiran_tgl)).' '.$masuk);
							$absen_masuk_telat = ($waktuMasuk > $aturanMasuk? date_diff($aturanMasuk, $waktuMasuk)->format("%h:%i:%S"): null);
						}

						$data_pulang = $abs->kehadiran->last(function($kode) use ($tgli){
							return $kode->kehadiran_kode == "1";
						});
						if($data_pulang)
							$data_pulang = $abs->kehadiran->last(function($tgl) use ($tgli){
								return substr($tgl->kehadiran_tgl, 0, 10) == $tgli;
							});
						$absen_pulang = $data_pulang? date('H:i:s', strtotime($data_pulang->kehadiran_tgl)): null;
						$absen_pulang_keterangan = $data_pulang? $data_pulang->kehadiran_keterangan: null;


						$data_istirahat = $abs->kehadiran->last(function($kode) use ($tgli){
							return $kode->kehadiran_kode == "1";
						});
						$absen_istirahat = $data_istirahat? date('H:i:s', strtotime($data_istirahat->kehadiran_tgl)): null;
						$absen_istirahat_keterangan = $data_istirahat? $data_istirahat->kehadiran_keterangan: null;
					}
					$data[] =[
						'pegawai_id' => $pegawai_id,
						'absen_tgl' => $absen_tgl,
						'absen_tgl_keterangan' => $absen_tgl_keterangan,
						'absen_hari' => $absen_hari,
						'absen_shift' => $absen_shift,
						'absen_masuk_telat' => $absen_masuk_telat,
						'absen_masuk' => $absen_masuk,
						'absen_masuk_keterangan' => $absen_masuk_keterangan,
						'absen_pulang' => $absen_pulang,
						'absen_pulang_keterangan' => $absen_pulang_keterangan,
						'absen_lembur' => $absen_lembur,
						'absen_lembur_keterangan' => $absen_lembur_keterangan,
						'absen_pulang_lembur' => $absen_pulang_lembur,
						'absen_pulang_lembur_keterangan' => $absen_pulang_lembur_keterangan,
						'absen_istirahat' => $absen_istirahat,
						'absen_istirahat_keterangan' => $absen_istirahat_keterangan,
						'absen_kembali' => $absen_kembali,
						'absen_kembali_keterangan' => $absen_kembali_keterangan,
						'absen_izin' => $absen_izin,
						'absen_izin_keterangan' => $absen_izin_keterangan,
					];
				}
			}
			foreach (array_chunk($data, 2000) as $t) {
				Absen::insert($t);
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
