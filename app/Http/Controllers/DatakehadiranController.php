<?php

namespace Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Absensi\Kehadiran;
use Absensi\Anggota;
use Absensi\Mesin;
use Illuminate\Support\Facades\DB;

class DatakehadiranController extends Controller
{
    //
    public function __construct()
	{
		$this->middleware('auth');
    	$this->middleware('permission:data kehadiran');
	}

    public function index(Request $req)
    {
    	$anggota = Anggota::all();
    	$pegawai = null;
    	if ($req->pegawai && $req->pegawai != '00') {
    		$pegawai = $req->pegawai;
    	}
    	$tgl1 = ($req->tgl1? date('Y-m-d', strtotime($req->tgl1)): date('Y-m-1'));
    	$tgl2 = ($req->tgl2? date('Y-m-d', strtotime($req->tgl2)): date('Y-m-t'));
    	$kehadiran = Kehadiran::when($pegawai != null, function ($q) use ($req){
    		return $q->where('pegawai_id', $req->pegawai);
    	})->whereRaw("date(kehadiran_tgl) between '".$tgl1."' and '".$tgl2."'")->paginate(10);

		$kehadiran->appends($req->tgl1);
		$kehadiran->appends($req->tgl2);
    	return view('pages.absensi.datakehadiran.index',[
    		'data' => $kehadiran,
    		'anggota' => $anggota,
    		'tgl1' => $tgl1,
    		'tgl2' => $tgl2,
    		'pegawai' => $req->pegawai
    	]);
    }

    public function download()
	{
		$mesin = Mesin::all();
		return view('pages.absensi.datakehadiran.download',[
			'data' => null,
			'mesin' => $mesin
		]);
	}

	private function parse($data,$p1,$p2){
		$data=" ".$data;
		$hasil="";
		$awal=strpos($data,$p1);
		if($awal!=""){
			$akhir=strpos(strstr($data,$p1),$p2);
			if($akhir!=""){
				$hasil=substr($data,$awal+strlen($p1),$akhir-strlen($p1));
			}
		}
		return $hasil;	
	}

	public function do_download(Request $req)
	{
		$req->validate(
			[
				'mesin_id' => 'required',
			],[
         	   'mesin_id.required' => 'Lokasi tidak boleh kosong',
        	]
		);
		try{
			$mesin = Mesin::find($req->mesin_id);

			$Connect = fsockopen($mesin->mesin_ip, "80", $errno, $errstr, 1);
			if($Connect){

				$soap_request="<GetAttLog><ArgComKey xsi:type=\"xsd:integer\">".$mesin->mesin_key."</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg></GetAttLog>";
				$newLine="\r\n";
				fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
			    fputs($Connect, "Content-Type: text/xml".$newLine);
			    fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
			    fputs($Connect, $soap_request.$newLine);
				$buffer="";
				while($Response=fgets($Connect, 1024)){
					$buffer=$buffer.$Response;
				}
			}else {
				return redirect('datakehadiran')
				->with('pesan', 'Gagal mendownload data kehadiran ('.$errno.')')
				->with('judul', 'Download data')
				->with('tipe', 'error');
			}
			$buffer = $this->parse($buffer,"<GetAttLogResponse>","</GetAttLogResponse>");
			$buffer = explode("\r\n",$buffer);
			Kehadiran::where('kantor_id', $mesin->kantor_id)->delete();
			for($i=0;$i<count($buffer);$i++){
				$data = $this->parse($buffer[$i],"<Row>","</Row>");
				if($data){
					$kehadiran = new Kehadiran();
					$kehadiran->kehadiran_id = date('Ymd', strtotime($this->parse($data,"<DateTime>","</DateTime>"))).$mesin->kantor_id.substr('00000'.$i, -4);
					$kehadiran->kantor_id = $mesin->kantor_id;
					$kehadiran->pegawai_id = (int)$this->parse($data,"<PIN>","</PIN>");
					$kehadiran->kehadiran_tgl = date_create_from_format('Y-m-d H:i:s', $this->parse($data,"<DateTime>","</DateTime>"));
					$kehadiran->kehadiran_kode = $this->parse($data,"<Status>","</Status>");
					$kehadiran->save();
				}
			}


			$soap_request="<ClearData><ArgComKey xsi:type=\"xsd:integer\">".$mesin->mesin_key."</ArgComKey><Arg><Value xsi:type=\"xsd:integer\">3</Value></Arg></ClearData>";
			$newLine="\r\n";
			fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
		    fputs($Connect, "Content-Type: text/xml".$newLine);
		    fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
		    fputs($Connect, $soap_request.$newLine);
			$buffer="";
			while($Response=fgets($Connect, 1024)){
				$buffer=$buffer.$Response;
			}

			return redirect('datakehadiran/download')
			->with('pesan', 'Berhasil mendownload data kehadiran '.$mesin->mesin_lokasi.'')
			->with('judul', 'Download data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect($req->get('redirect')? $req->get('redirect'): 'datakehadiran/download')
			->with('pesan', 'Gagal mendownload data kehadiran '.$mesin->mesin_lokasi.'. Error: '.$e)
			->with('judul', 'Download data')
			->with('tipe', 'error');
		}
	}

	public function hapus($id)
	{
		try{
			Kehadiran::destroy($id);
			return redirect()->back()
			->with('pesan', 'Berhasil menghapus data kehadiran (lokasi:'.$id.')')
			->with('judul', 'Hapus data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect()->back()
			->with('pesan', 'Gagal menghapus data kehadiran (lokasi:'.$req->get('kehadiran_id').') Error: '.$e)
			->with('judul', 'Hapus data')
			->with('tipe', 'error');
		}
	}
}
