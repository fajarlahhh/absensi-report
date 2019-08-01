<?php

namespace Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Absensi\Anggota;
use Absensi\Bagian;
use Absensi\Absen;
use Illuminate\Support\Facades\DB;

class RekapabsensiController extends Controller
{
    public function index(Request $req)
    {
        $tanggal = explode(' - ', $req->get('tgl'));
        $tgl1 = ($req->get('tgl')? date('Y-m-d', strtotime($tanggal[0])): date('Y-m-01'));
        $tgl2 = ($req->get('tgl')? date('Y-m-d', strtotime($tanggal[1])): date('Y-m-d'));
        $diff = date_diff(date_create($tgl1), date_create($tgl2))->format("%a") + 1;
        $bagian = Bagian::all();
        $bag = $req->get('bag')? $req->get('bag'): $bagian{0}->kd_bagian;
        $absensi = Anggota::with(['absen' => function($q) use($tgl1, $tgl2){            
            $q->selectRaw("pegawai_id, sum(if(absen_hari = 'l', 0, 1)) `hari`, 
                sum(if(absen_masuk_telat, 1, 0)) `telat`, 
                sum(if(absen_masuk, 1, 0)) `masuk`, 
                sum(if(absen_izin = 'Sakit', 1, 0)) `sakit`, 
                sum(if(absen_izin = 'Izin', 1, 0)) `izin`, 
                sum(if(absen_izin = 'Dispensasi', 1, 0)) `dispensasi`, 
                sum(if(absen_izin = 'Tugas Dinas', 1, 0)) `dinas`, 
                sum(if(absen_izin = 'Cuti', 1, 0)) `cuti`, 
                sum(if(absen_hari = 'l', 0, if(absen_izin = 'Tanpa Keterangan', 1, 0))) `tanpaketerangan`");
            $q->whereRaw("absen_tgl between '".$tgl1."' and '".$tgl2."'");
            $q->groupBy('pegawai_id');
        }])->with('pegawai')->whereHas('pegawai', function($q) use($bag){
            $q->where('kd_bagian', $bag);
        })->select('pegawai_id')->groupBy('pegawai_id')->get();
    	return view('pages.laporan.rekapabsensi.index',[
            'bagian' => $bagian,
            'bag' => $bag,
            'absensi' => $absensi,
            'tgl' => date('d F Y', strtotime($tgl1)).' - '.date('d F Y', strtotime($tgl2))
    	]);
    }
}
