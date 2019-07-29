<?php

namespace Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Absensi\Anggota;
use Absensi\Absen;
use Absensi\Kantor;
use Illuminate\Support\Facades\DB;

class RinciankehadiranController extends Controller
{
    public function index(Request $req)
    {
        $tanggal = explode(' - ', $req->get('tgl'));
        $tgl1 = ($req->get('tgl')? date('Y-m-d', strtotime($tanggal[0])): date('Y-m-1'));
        $tgl2 = ($req->get('tgl')? date('Y-m-d', strtotime($tanggal[1])): date('Y-m-d'));
        $diff = date_diff(date_create($tgl1), date_create($tgl2))->format("%a") + 1;
        $kantor = Kantor::all();
        $ktr = $req->get('ktr')?$req->get('ktr'):$kantor{0}->kantor_id;
        $absensi = Anggota::with('absen')->with('pegawai')->where('kantor_id', $ktr)->groupBy('anggota_id')->orderBy('anggota_nip')->get();
    	return view('pages.laporan.rincianabsensi.index',[
            'diff' => $diff,
            'kantor' => $kantor,
            'idkantor' => $ktr,
            'absensi' => $absensi,
            'tgl' => date('d F Y', strtotime($tgl1)).' - '.date('d F Y', strtotime($tgl2))
    	]);
    }

    public function tampil(Request $req)
    {
        $tgl1 = ($req->tgl1? date('Y-m-d', strtotime($req->tgl1)): date('Y-m-1'));
        $tgl2 = ($req->tgl2? date('Y-m-d', strtotime($req->tgl2)): date('Y-m-d'));
        $diff = date_diff(date_create($tgl1), date_create($tgl2))->format("%a") + 1;
        $absensi = [];
        $anggota = Anggota::groupBy('anggota_id')->get();
        $x=0;
        foreach ($anggota as $key => $angg) {
            $absensi[$x][0] = $angg->pegawai->nip;
            $absensi[$x][1] = $angg->pegawai->nm_pegawai;
            $absen = Absen::where('pegawai_id', $angg->pegawai_id)->whereBetween('absen_tgl', [$tgl1, $tgl2])->get();
            $absensi[$x][2] = $absen;
            $x++;
        }
        return view('pages.laporan.rincianabsensi.tampil',[
            'absensi' => $absensi,
            'aturan' => $aturan,
            'diff' => $diff,
            'tgl1' => $tgl1,
            'tgl2' => $tgl2
        ]);
    }
}
