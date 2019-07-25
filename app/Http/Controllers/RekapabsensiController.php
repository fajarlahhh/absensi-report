<?php

namespace Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Absensi\Anggota;
use Absensi\Kantor;
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
        $kantor = Kantor::all();
        $ktr = $req->get('ktr')?$req->get('ktr'):$kantor{0}->kantor_id;
        $absensi = Anggota::with('pegawai')->selectRaw("anggota_nip, sum(if(absen_hari = 'b', 1, 0)) `hari`, 
                sum(if(absen_masuk_telat, 1, 0)) `telat`, 
                sum(if(absen_masuk, 1, 0)) `masuk`, 
                sum(if(absen_izin = 'Sakit', 1, 0)) `sakit`, 
                sum(if(absen_izin = 'Izin', 1, 0)) `izin`, 
                sum(if(absen_izin = 'Dispensasi', 1, 0)) `dispensasi`, 
                sum(if(absen_izin = 'Tugas Dinas', 1, 0)) `dinas`, 
                sum(if(absen_izin = 'Cuti', 1, 0)) `cuti`, 
                sum(if(absen_izin = 'Lain-lain', 1, 0)) `lain`")->leftJoin('absen', 'absen.pegawai_id', '=', 'anggota.pegawai_id')->where('kantor_id', $ktr)->whereBetween('absen_tgl', [$tgl1, $tgl2])->groupBy('anggota_nip')->orderBy('anggota_nip')->get();
    	return view('pages.laporan.rekapabsensi.index',[
            'diff' => $diff,
            'kantor' => $kantor,
            'idkantor' => $ktr,
            'absensi' => $absensi,
            'tgl' => date('d F Y', strtotime($tgl1)).' - '.date('d F Y', strtotime($tgl2))
    	]);
    }
}
