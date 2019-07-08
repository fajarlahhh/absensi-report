<?php

namespace Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Absensi\Anggota;
use Absensi\Absen;
use Illuminate\Support\Facades\DB;

class RekapabsensiController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:rekap absensi');
    }

    public function index(Request $req)
    {
    	
        $tanggal = explode(' - ', $req->get('tgl'));
        $tgl1 = ($req->get('tgl')? date('Y-m-d', strtotime($tanggal[0])): date('Y-m-01'));
        $tgl2 = ($req->get('tgl')? date('Y-m-d', strtotime($tanggal[1])): date('Y-m-d'));
        $diff = date_diff(date_create($tgl1), date_create($tgl2))->format("%a") + 1;
        $absensi = [];
        $anggota = Anggota::get();
        $x=0;
        foreach ($anggota as $key => $angg) {
            $absensi[$x][0] = $angg->pegawai->nip;
            $absensi[$x][1] = $angg->pegawai->nm_pegawai;
            $absen = Absen::selectRaw("
                sum(if(absen_hari = 'b', 1, 0)) `hari`, 
                sum(if(absen_masuk_telat, 1, 0)) `telat`, 
                sum(if(absen_masuk, 1, 0)) `masuk`, 
                sum(if(absen_izin = 'Sakit', 1, 0)) `sakit`, 
                sum(if(absen_izin = 'Izin', 1, 0)) `izin`, 
                sum(if(absen_izin = 'Dispensasi', 1, 0)) `dispensasi`, 
                sum(if(absen_izin = 'Tugas Dinas', 1, 0)) `dinas`, 
                sum(if(absen_izin = 'Cuti', 1, 0)) `cuti`, 
                sum(if(absen_izin = 'Lain-lain', 1, 0)) `lain`")->where('pegawai_id', $angg->pegawai_id)->whereBetween('absen_tgl', [$tgl1, $tgl2])->groupBy('pegawai_id')->get();
            $absensi[$x][2] = null;
            foreach ($absen as $key => $abs) {
                $absensi[$x][2] = $absen;
            }
        }
    	return view('pages.laporan.rekapabsensi.index',[
            'diff' => $diff,
            'absensi' => $absensi,
            'tgl' => date('d F Y', strtotime($tgl1)).' - '.date('d F Y', strtotime($tgl2))
    	]);
    }
}
