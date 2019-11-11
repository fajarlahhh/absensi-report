<?php

namespace Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Absensi\Anggota;
use Absensi\Absen;
use Absensi\Bagian;
use Illuminate\Support\Facades\DB;
use PDF;

class RinciankehadiranController extends Controller
{
    public function index(Request $req)
    {
        $tanggal = explode(' - ', $req->get('tgl'));
        $tgl1 = ($req->get('tgl')? date('Y-m-d', strtotime($tanggal[0])): date('Y-m-1'));
        $tgl2 = ($req->get('tgl')? date('Y-m-d', strtotime($tanggal[1])): date('Y-m-d'));
        $bagian = Bagian::all();
        $bag = $req->get('bag')? $req->get('bag'): $bagian{0}->kd_bagian;
        $absensi = Anggota::with(['absen' => function($q) use($tgl1, $tgl2){
            $q->whereBetween('absen_tgl', [$tgl1, $tgl2]);
            $q->orderBy('absen_tgl');
        }])->with('pegawai')->whereHas('pegawai', function($q) use($bag){
            $q->where('kd_bagian', $bag);
        })->select('pegawai_id')->groupBy('pegawai_id')->get();
    	return view('pages.laporan.rincianabsensi.index',[
            'bagian' => $bagian,
            'bag' => $bag,
            'absensi' => $absensi,
            'tgl' => date('d F Y', strtotime($tgl1)).' - '.date('d F Y', strtotime($tgl2))
    	]);
    }    

    public function pdf(Request $req)
    {
        $tanggal = explode(' - ', $req->get('tgl'));
        $tgl1 = ($req->get('tgl')? date('Y-m-d', strtotime($tanggal[0])): date('Y-m-1'));
        $tgl2 = ($req->get('tgl')? date('Y-m-d', strtotime($tanggal[1])): date('Y-m-d'));
        $bagian = Bagian::all();
        $bag = $req->get('bag')? $req->get('bag'): $bagian{0}->kd_bagian;
        $absensi = Anggota::with(['absen' => function($q) use($tgl1, $tgl2){
            $q->whereBetween('absen_tgl', [$tgl1, $tgl2]);
        }])->with('pegawai')->whereHas('pegawai', function($q) use($bag){
            $q->where('kd_bagian', $bag);
        })->select('pegawai_id')->groupBy('pegawai_id')->get();
        $pdf = PDF::loadView('pages.laporan.rincianabsensi.pdf', [
            'absensi' => $absensi,
            'tanggal' => $req->get('tgl'),
            'bag' => $bag,
            'bagian' => $bagian,
        ], [], [
            'format' => 'A4-L'
        ]);
        return $pdf->stream('Rincian absensi bagian '.($bagian->first(function($q)use($bag){ return $q->kd_bagian == $bag; })).' '.$req->get('tgl').'.pdf');
    }

    public function tampil(Request $req)
    {
        $tgl1 = ($req->tgl1? date('Y-m-d', strtotime($req->tgl1)): date('Y-m-1'));
        $tgl2 = ($req->tgl2? date('Y-m-d', strtotime($req->tgl2)): date('Y-m-d'));
        $diff = date_diff(date_create($tgl1), date_create($tgl2))->format("%a") + 1;
        $absensi = Anggota::with(['absen' => function($q) use($tgl1, $tgl2){
            $q->whereBetween('absen_tgl', [$tgl1, $tgl2]);
        }])->with('pegawai')->where('anggota_nip', $req->nip)->groupBy('anggota_id')->orderBy('anggota_nip')->first();
        return view('pages.laporan.rincianabsensi.tampil',[
            'absensi' => $absensi,
            'diff' => $diff,
            'tgl1' => $tgl1,
            'tgl2' => $tgl2
        ]);
    }

    public function list_pegawai(Request $req)
    {
        $data = Absen::where('absen_izin', $req->jenis)->with(['anggota' => function($q){
            $q->with(['pegawai' => function($q1) {
                $q1->with('unit');
                $q1->with('bagian');
                $q1->with('jabatan');
                $q1->with('seksi');
            }]);
        }])->whereBetween('absen_tgl', [$req->tgl1, $req->tgl2])->orderBy('pegawai_id')->get();
        $list = [];
        foreach ($data as $key => $row) {
            array_push($list, [
                'nik' => $row->anggota->anggota_nip,
                'nama' => $row->anggota->pegawai->nm_pegawai,
                'unit' => $row->anggota->pegawai->unit->nm_unit,
                'bagian' => $row->anggota->pegawai->bagian->nm_bagian,
                'jabatan' => $row->anggota->pegawai->jabatan->nm_jabatan,
                'seksi' => $row->anggota->pegawai->seksi? $row->anggota->pegawai->seksi->nm_seksi: '',
                'tanggal' => $row->absen_tgl,
            ]);
        }
        return response()->json($list);
    }
}
