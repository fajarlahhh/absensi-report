<?php

namespace Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Absensi\Pegawai;
use Absensi\Kantor;
use Absensi\Absen;
use PDF;

class RekapabsensiController extends Controller
{
    public function index(Request $req)
    {
        $tanggal = explode(' - ', $req->get('tgl'));
        $tgl1 = ($req->get('tgl')? date('Y-m-d', strtotime($tanggal[0])): date('Y-m-01'));
        $tgl2 = ($req->get('tgl')? date('Y-m-d', strtotime($tanggal[1])): date('Y-m-d'));
        $diff = date_diff(date_create($tgl1), date_create($tgl2))->format("%a") + 1;
        $kantor = Kantor::orderBy('kantor_nama', 'asc')->get();
        $ktr = $req->get('ktr')? $req->get('ktr'): $kantor{0}->kantor_id;
        $absensi = Pegawai::with(['absen' => function($q) use($tgl1, $tgl2){            
            $q->selectRaw("pegawai_nip, sum(if(absen_hari = 'l', 0, 1)) `hari`, 
                sum(if(absen_telat, 1, 0)) `telat`, 
                sum(if(absen_masuk, 1, 0)) `masuk`, 
                sum(if(absen_izin = 'Sakit', 1, 0)) `sakit`, 
                sum(if(absen_izin = 'Izin', 1, 0)) `izin`, 
                sum(if(absen_izin = 'Dispensasi', 1, 0)) `dispensasi`, 
                sum(if(absen_izin = 'Tugas Dinas', 1, 0)) `dinas`, 
                sum(if(absen_izin = 'Cuti', 1, 0)) `cuti`, 
                sum(if(absen_hari = 'l', 0, if(absen_izin = 'Tanpa Keterangan', 1, 0))) `tanpaketerangan`");
            $q->whereRaw("absen_tanggal between '".$tgl1."' and '".$tgl2."'");
            $q->groupBy('pegawai_nip');
        }])->where('kantor_id', $ktr)->select('pegawai_nip','pegawai_nama','pegawai_golongan','pegawai_jenis_kelamin')->groupBy('pegawai_nip','pegawai_nip','pegawai_nama','pegawai_golongan','pegawai_jenis_kelamin')->get();
    	return view('pages.laporan.rekapabsensi.index',[
            'kantor' => $kantor,
            'ktr' => $ktr,
            'absensi' => $absensi,
            'tgl' => date('d F Y', strtotime($tgl1)).' - '.date('d F Y', strtotime($tgl2))
    	]);
    }
    public function pdf(Request $req)
    {
        $tanggal = explode(' - ', $req->get('tgl'));
        $tgl1 = ($req->get('tgl')? date('Y-m-d', strtotime($tanggal[0])): date('Y-m-01'));
        $tgl2 = ($req->get('tgl')? date('Y-m-d', strtotime($tanggal[1])): date('Y-m-d'));
        $diff = date_diff(date_create($tgl1), date_create($tgl2))->format("%a") + 1;
        $bagian = Bagian::all();
        $bag = $req->get('bag')? $req->get('bag'): $bagian{0}->kd_bagian;
        $absensi = Anggota::with(['absen' => function($q) use($tgl1, $tgl2){            
            $q->selectRaw("pegawai_nip, sum(if(absen_hari = 'l', 0, 1)) `hari`, 
                sum(if(absen_masuk_telat, 1, 0)) `telat`, 
                sum(if(absen_masuk, 1, 0)) `masuk`, 
                sum(if(absen_izin = 'Sakit', 1, 0)) `sakit`, 
                sum(if(absen_izin = 'Izin', 1, 0)) `izin`, 
                sum(if(absen_izin = 'Dispensasi', 1, 0)) `dispensasi`, 
                sum(if(absen_izin = 'Tugas Dinas', 1, 0)) `dinas`, 
                sum(if(absen_izin = 'Cuti', 1, 0)) `cuti`, 
                sum(if(absen_hari = 'l', 0, if(absen_izin = 'Tanpa Keterangan', 1, 0))) `tanpaketerangan`");
            $q->whereRaw("absen_tgl between '".$tgl1."' and '".$tgl2."'");
            $q->groupBy('pegawai_nip');
        }])->with('pegawai')->whereHas('pegawai', function($q) use($bag){
            $q->where('kd_bagian', $bag);
        })->select('pegawai_nip')->groupBy('pegawai_nip')->get();
        $pdf = PDF::loadView('pages.laporan.rekapabsensi.pdf', [
            'absensi' => $absensi,
            'tanggal' => $req->get('tgl'),
            'bag' => $bag,
            'bagian' => $bagian,
        ], [], [
            'format' => 'A4-L'
        ]);
        return $pdf->stream('Rekap absensi bagian '.($bagian->first(function($q)use($bag){ return $q->kd_bagian == $bag; })).' '.$req->get('tgl').'.pdf');
    }

    public function rekap_pertanggal(Request $req)
    {
        $rekap = Absen::selectRaw("sum(if(absen_masuk_telat is not null, 1, 0)) telat,
        sum(if(absen_izin = 'Tanpa Keterangan', 1, 0)) tk,
        sum(if(absen_izin = 'Sakit', 1, 0)) sakit,
        sum(if(absen_izin = 'Cuti', 1, 0)) cuti,
        sum(if(absen_izin = 'Izin', 1, 0)) izin,
        sum(if(absen_izin = 'Tugas Dinas', 1, 0)) td")->where('absen_hari', 'b')->whereBetween('absen_tgl', [$req->tgl1, $req->tgl2])->get();

        return response()->json($rekap);
    }

    public function do_tambah(Request $req)
    {
        $req->validate(
			[
				'pegawai_nip' => 'required',
				'absen_tanggal' => 'required',
			],[
         	   'pegawai_nip.required' => 'NIP tidak boleh kosong',
         	   'absen_tanggal.required' => 'Tanggal tidak boleh kosong',
        	]
		);
		try{
            Absen::where('pegawai_nip', $req->get('pegawai_nip'))->where('absen_tanggal', $req->get('absen_tanggal'))->delete();

			$absen = new Absen();
			$absen->pegawai_nip = $req->get('pegawai_nip');
			$absen->absen_tanggal = $req->get('absen_tanggal');
			$absen->absen_hari = $req->get('absen_hari');
			$absen->absen_izin = $req->get('absen_izin');
			$absen->absen_telat = $req->get('absen_telat') == "00:00:00"? null: $req->get('absen_telat');
			$absen->absen_masuk = $req->get('absen_masuk') == "00:00:00"? null: $req->get('absen_masuk');
			$absen->absen_pulang = $req->get('absen_pulang') == "00:00:00"? null: $req->get('absen_pulang');
			$absen->absen_istirahat = $req->get('absen_istirahat') == "00:00:00"? null: $req->get('absen_istirahat');
			$absen->absen_kembali = $req->get('absen_kembali') == "00:00:00"? null: $req->get('absen_kembali');
			$absen->absen_lembur = $req->get('absen_lembur') == "00:00:00"? null: $req->get('absen_lembur');
			$absen->absen_lembur_pulang = $req->get('absen_lembur_pulang') == "00:00:00"? null: $req->get('absen_lembur_pulang');
			$absen->kantor_id = $req->get('kantor_id');
			$absen->save();
			$response = [
				'berhasil' => 'berhasil'
			];
	
			return response()->json($response);
		}catch(\Exception $e){
			$response = [
				'status' => $e
			];
	
			return response()->json($response);
		}
    }
}
