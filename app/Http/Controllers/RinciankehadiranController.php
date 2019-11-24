<?php

namespace Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Absensi\Pegawai;
use Absensi\Kantor;
use PDF;

class RinciankehadiranController extends Controller
{
    public function index(Request $req)
    {
        $tanggal = explode(' - ', $req->get('tgl'));
        $tgl1 = ($req->get('tgl')? date('Y-m-d', strtotime($tanggal[0])): date('Y-m-1'));
        $tgl2 = ($req->get('tgl')? date('Y-m-d', strtotime($tanggal[1])): date('Y-m-d'));
        $kantor = Kantor::orderBy('kantor_nama', 'asc')->get();
        $ktr = $req->get('ktr')? $req->get('ktr'): $kantor{0}->kantor_id;
        $absensi = Pegawai::with(['absen' => function($q) use($tgl1, $tgl2){
            $q->whereBetween('absen_tanggal', [$tgl1, $tgl2]);
            $q->orderBy('absen_tanggal');
        }])->with('kantor')->whereHas('kantor', function($q) use($ktr){
            $q->where('kantor_id', $ktr);
        })->select('pegawai_nip','pegawai_nama','pegawai_golongan','pegawai_jenis_kelamin')->groupBy('pegawai_nip','pegawai_nip','pegawai_nama','pegawai_golongan','pegawai_jenis_kelamin')->get();
    	return view('pages.laporan.rincianabsensi.index',[
            'kantor' => $kantor,
            'ktr' => $ktr,
            'absensi' => $absensi,
            'tgl' => date('d F Y', strtotime($tgl1)).' - '.date('d F Y', strtotime($tgl2))
    	]);
    }    

    public function pdf(Request $req)
    {
        $tanggal = explode(' - ', $req->get('tgl'));
        $tgl1 = ($req->get('tgl')? date('Y-m-d', strtotime($tanggal[0])): date('Y-m-1'));
        $tgl2 = ($req->get('tgl')? date('Y-m-d', strtotime($tanggal[1])): date('Y-m-d'));
        $kantor = Kantor::orderBy('kantor_nama', 'asc')->get();
        $ktr = $req->get('ktr')? $req->get('ktr'): $kantor{0}->kantor_id;
        $absensi = Pegawai::with(['absen' => function($q) use($tgl1, $tgl2){
            $q->whereBetween('absen_tanggal', [$tgl1, $tgl2]);
            $q->orderBy('absen_tanggal');
        }])->with('kantor')->whereHas('kantor', function($q) use($ktr){
            $q->where('kantor_id', $ktr);
        })->select('pegawai_nip','pegawai_nama','pegawai_golongan','pegawai_jenis_kelamin')->groupBy('pegawai_nip','pegawai_nip','pegawai_nama','pegawai_golongan','pegawai_jenis_kelamin')->get();
        $pdf = PDF::loadView('pages.laporan.rincianabsensi.pdf', [
            'absensi' => $absensi,
            'tanggal' => $req->get('tgl'),
            'ktr' => $ktr,
            'kantor' => $kantor,
        ], [], [
            'format' => 'A4-L'
        ]);
        return $pdf->stream('Rincian absensi kantor '.($kantor->first(function($q)use($bag){ return $q->kantor_id == $ktr; })).' '.$req->get('tgl').'.pdf');
    }
}
