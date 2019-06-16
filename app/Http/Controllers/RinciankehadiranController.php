<?php

namespace Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Absensi\Anggota;
use Absensi\Kehadiran;
use Absensi\Aturan;
use Illuminate\Support\Facades\DB;

class RinciankehadiranController extends Controller
{
    //
    public function __construct()
	{
		$this->middleware('auth');
    	$this->middleware('permission:rincian kehadiran');
	}

    public function index(Request $req)
    {
    	$tgl1 = ($req->tgl1? date('Y-m-d', strtotime($req->tgl1)): date('Y-m-1'));
    	$tgl2 = ($req->tgl2? date('Y-m-d', strtotime($req->tgl2)): date('Y-m-d'));
    	$diff = date_diff(date_create($tgl1), date_create($tgl2))->format("%a");

    	$absensi = [];
    	$anggota = Anggota::select('pegawai_id')->get();
    	$aturan = Aturan::first();
    	$j = 0;
    	foreach ($anggota as $index => $angg){
    		$absensi[$j][0] = $angg->pegawai->nip;
    		$absensi[$j][1] = $angg->pegawai->nm_pegawai;

    		for($i=2; $i <= $diff + 2; $i++){
    			$kehadiran = Kehadiran::selectRaw('ifnull(DATE_FORMAT(kehadiran_tgl,\'%H:%i:%s\'), "") waktu')
			    	->where('pegawai_id', $angg->pegawai_id)
			    	->whereRaw("date(kehadiran_tgl) = '".date('Y-m-d', strtotime($tgl1. ' + '.$i.' days'))."'")
			    	->orderBy('kehadiran_tgl', 'asc')
			    	->where('kehadiran_kode', 0)
			    	->first();
    			$absensi[$j][$i] = ($kehadiran? $kehadiran->waktu: '');
    		}
    		$j++;
    	}

    	return view('pages.laporan.rincianabsensi.index',[
    		'absensi' => $absensi,
    		'aturan' => $aturan,
			'diff' => $diff,
    		'tgl1' => $tgl1,
    		'tgl2' => $tgl2
    	]);
    }
}
