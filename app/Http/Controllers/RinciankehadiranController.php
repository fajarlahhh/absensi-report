<?php

namespace Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Absensi\Anggota;
use Absensi\Kehadiran;
use Absensi\Aturan;
use Absensi\Libur;
use Absensi\TglKhusus;
use Illuminate\Support\Facades\DB;

class RinciankehadiranController extends Controller
{
    //

    public function index(Request $req)
    {
    	$tgl1 = ($req->tgl1? date('Y-m-d', strtotime($req->tgl1)): date('Y-m-1'));
    	$tgl2 = ($req->tgl2? date('Y-m-d', strtotime($req->tgl2)): date('Y-m-d'));
    	$diff = date_diff(date_create($tgl1), date_create($tgl2))->format("%a") + 1;
    	$absensi = [];
    	$anggota = Anggota::select('pegawai_id')->get();
    	$aturan = Aturan::first();
        $libur = Libur::whereRaw("date(libur_tgl) between '".$tgl1."' and '".$tgl2."'")->get();
        $khusus = TglKhusus::select('tgl_khusus_waktu')->whereRaw("tgl_khusus_waktu between '".$tgl1."' and '".$tgl2."'")->get();
    	$j = 0;
    	foreach ($anggota as $index => $angg){
    		$absensi[$j][0] = $angg->pegawai->nip;
    		$absensi[$j][1] = $angg->pegawai->nm_pegawai;

    		for($i=2; $i < $diff + 2; $i++){
    			$kehadiran = Kehadiran::selectRaw('ifnull(DATE_FORMAT(kehadiran_tgl,\'%H:%i:%s\'), "") waktu, concat(kehadiran_kode, \' - \', kehadiran_keterangan) kehadiran_kode' )
			    	->whereIn('pegawai_id', [$angg->pegawai_id])
			    	->whereRaw("date(kehadiran_tgl) = '".date('Y-m-d', strtotime($tgl1. ' + '.($i-2).' days'))."'")
			    	->orderBy('kehadiran_tgl', 'asc')
                    ->where(function($query) {
                         $query->where('kehadiran_kode', 0);
                         $query->orWhere('kehadiran_status', 'I');
                     })
			    	->first();

    			$absensi[$j][$i] = ($kehadiran? ($kehadiran->waktu == '00:00:00'? $kehadiran->kehadiran_kode: $kehadiran->waktu): '');
    		}
    		$j++;   	
        }
    	return view('pages.laporan.rincianabsensi.index',[
    		'absensi' => $absensi,
            'aturan' => $aturan,
            'libur' => $libur,
            'khusus' => $khusus,
			'diff' => $diff,
    		'tgl1' => $tgl1,
    		'tgl2' => $tgl2
    	]);
    }

    public function tampil(Request $req)
    {
        $tgl1 = ($req->tgl1? date('Y-m-d', strtotime($req->tgl1)): date('Y-m-1'));
        $tgl2 = ($req->tgl2? date('Y-m-d', strtotime($req->tgl2)): date('Y-m-d'));
        $diff = date_diff(date_create($tgl1), date_create($tgl2))->format("%a") + 1;
        $hari = $req->hari ? $req->hari: 1;
        $anggota = Anggota::select('pegawai_id', 'anggota_nip')->where('anggota_nip', $req->nip)->first();
        $aturan = Aturan::first();
        $libur = Libur::whereRaw("date(libur_tgl) between '".$tgl1."' and '".$tgl2."'")->get();
        $khusus = TglKhusus::select('tgl_khusus_waktu')->whereRaw("tgl_khusus_waktu between '".$tgl1."' and '".$tgl2."'")->get();
        $absensi = [];
        if($anggota){
            $absensi[0] = $anggota->pegawai->nip;
            $absensi[1] = $anggota->pegawai->nm_pegawai;
            for($i=2; $i < $diff + 2; $i++){
                $kehadiran = Kehadiran::selectRaw('ifnull(DATE_FORMAT(kehadiran_tgl,\'%H:%i:%s\'), "") waktu, concat(kehadiran_kode, \' - \', kehadiran_keterangan) kehadiran_kode' )
                    ->whereIn('pegawai_id', [$anggota->pegawai_id])
                    ->whereRaw("date(kehadiran_tgl) = '".date('Y-m-d', strtotime($tgl1. ' + '.($i-2).' days'))."'")
                    ->orderBy('kehadiran_tgl', 'asc')
                    ->where(function($query) {
                         $query->where('kehadiran_kode', 0);
                         $query->orWhere('kehadiran_status', 'I');
                     })
                    ->first();

                $absensi[$i] = ($kehadiran? ($kehadiran->waktu == '00:00:00'? $kehadiran->kehadiran_kode: $kehadiran->waktu): '');
            }
        }
        return view('pages.laporan.rincianabsensi.tampil',[
            'absensi' => $absensi,
            'aturan' => $aturan,
            'libur' => $libur,
            'khusus' => $khusus,
            'hari' => $hari,
            'diff' => $diff,
            'tgl1' => $tgl1,
            'tgl2' => $tgl2
        ]);
    }
}
