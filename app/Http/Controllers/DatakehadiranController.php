<?php

namespace Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Absensi\Kehadiran;
use Absensi\Anggota;
use Absensi\Kantor;
use Absensi\Mesin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

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
    	$tgl2 = ($req->tgl2? date('Y-m-d', strtotime($req->tgl2)): date('Y-m-d'));
    	$kehadiran = Kehadiran::when($pegawai != null, function ($q) use ($req){
    		return $q->where('pegawai_id', $req->pegawai);
    	})
    	->whereIn('kehadiran_status', ['M', 'T'])
    	->whereRaw("date(kehadiran_tgl) between '".$tgl1."' and '".$tgl2."'")->paginate(10);

		$kehadiran->appends(['tgl1' => $req->tgl1, 'tgl2' => $req->tgl2]);
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
		$kantor = Kantor::all();
		return view('pages.absensi.datakehadiran.download',[
			'data' => null,
			'kantor' => $kantor
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
		$validator = Validator::make($req->all(),[
			'kantor_id' => 'required',
		],[
     	    'kantor_id.required' => 'Kantor tidak boleh kosong',
    	]);

    	if($validator->fails()){
			return \Response::json([
				'pesan' => $validator->errors()->first(),
				'tipe' => 'error'
			]);
    	}
		try{
			ini_set('max_execution_time', 300);
			$mesin = Mesin::where('kantor_id', $req->kantor_id)->get();
			if(count($mesin) > 0){
				$anggota = Anggota::where('kantor_id', $req->kantor_id)->get();
				$template = [];
				$i = 0;

				foreach ($mesin as $key => $msn) {
					$Connect = fsockopen($msn->mesin_ip, "80", $errno, $errstr, 1);
					if($Connect){

						$soap_request="<GetAttLog><ArgComKey xsi:type=\"xsd:integer\">".$msn->mesin_key."</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg></GetAttLog>";
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
						return \Response::json([
							'pesan' => 'Gagal menghapus data kehadiran ('.$errno.')',
							'tipe' => 'error'
						]);
					}
					$buffer = $this->parse($buffer,"<GetAttLogResponse>","</GetAttLogResponse>");
					$buffer = explode("\r\n",$buffer);
					for($i=0;$i<count($buffer);$i++){
						$data = $this->parse($buffer[$i],"<Row>","</Row>");
						if($data){
							$kehadiran = new Kehadiran();
							$kehadiran->kantor_id = $msn->kantor_id;
							$kehadiran->pegawai_id = (int)$this->parse($data,"<PIN>","</PIN>");
							$kehadiran->kehadiran_tgl =  $this->parse($data,"<DateTime>","</DateTime>");
							$kehadiran->kehadiran_kode = $this->parse($data,"<Status>","</Status>");
		    				$kehadiran->kehadiran_status = 'M';
		    				$kehadiran->operator = Auth::user()->pegawai->nm_pegawai;
							$kehadiran->save();
						}
					}

					$Connect1 = fsockopen($msn->mesin_ip, "80", $errno, $errstr, 1);
					if($Connect1){
						$soap_request="<ClearData><ArgComKey xsi:type=\"xsd:integer\">".$msn->mesin_key."</ArgComKey><Arg><Value xsi:type=\"xsd:integer\">3</Value></Arg></ClearData>";
						$newLine="\r\n";
						fputs($Connect1, "POST /iWsService HTTP/1.0".$newLine);
					    fputs($Connect1, "Content-Type: text/xml".$newLine);
					    fputs($Connect1, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
					    fputs($Connect1, $soap_request.$newLine);
						$buffer1="";
						while($Response1=fgets($Connect1, 1024)){
							$buffer1=$buffer1.$Response1;
						}
					}else {
						return \Response::json([
							'pesan' => 'Gagal menghapus data kehadiran ('.$errno.')',
							'tipe' => 'error'
						]);
					}
				}
				return \Response::json([
					'pesan' => 'Proses download kehadiran berhasil',
					'tipe' => 'success'
				]);
			}else{
				return \Response::json([
					'pesan' => 'Data mesin tidak tersedia untuk kantor ini',
					'tipe' => 'error'
				]);	
			}
		} catch (Exception $e) {
			return \Response::json([
				'pesan' => $e->getMessage(),
				'tipe' => 'error'
			]);	
		}
	}


    public function tambah()
	{
    	$anggota = Anggota::all();
		return view('pages.absensi.datakehadiran.form',[
    		'anggota' => $anggota
		]);
	}

	public function do_tambah(Request $req)
	{
		$req->validate(
			[
				'pegawai_id' => 'required',
				'kehadiran_tgl' => 'required',
				'kehadiran_kode' => 'required',
				'kehadiran_keterangan' => 'required'
			],[
         	   'pegawai_id.required' => 'Anggota tidak boleh kosong',
         	   'kehadiran_tgl.required' => 'Tanggal Izin tidak boleh kosong',
         	   'kehadiran_kode.required' => 'Alasan tidak boleh kosong',
         	   'kehadiran_keterangan.required' => 'Keterangan tidak boleh kosong',
        	]
		);
		try{
			$kehadiran = new Kehadiran(); 
			$kehadiran->pegawai_id = $req->get('pegawai_id');
			$kehadiran->kehadiran_tgl = date('Y-m-d H:i:s', strtotime($req->get('kehadiran_tgl')));
			$kehadiran->kehadiran_kode = $req->get('kehadiran_kode');
			$kehadiran->kehadiran_keterangan = $req->get('kehadiran_keterangan');
			$kehadiran->kehadiran_status = 'T';
    		$kehadiran->operator = Auth::user()->pegawai->nm_pegawai;

			$kehadiran->save();
			return redirect($req->get('redirect')? $req->get('redirect'): 'datakehadiran')
			->with('pesan', 'Berhasil menambah data kehadiran')
			->with('judul', 'Tambah data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect($req->get('redirect')? $req->get('redirect'): 'datakehadiran')
			->with('pesan', 'Gagal menambah data kehadiran. Error: '.$e->getMessage())
			->with('judul', 'Tambah data')
			->with('tipe', 'error');
		}
	}

	public function hapus($id)
	{
		try{
			Kehadiran::destroy($id);
			return redirect()->back()
			->with('pesan', 'Berhasil menghapus data kehadiran (ID:'.$id.')')
			->with('judul', 'Hapus data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect()->back()
			->with('pesan', 'Gagal menghapus data kehadiran (ID:'.$id.') Error: '.$e->getMessage())
			->with('judul', 'Hapus data')
			->with('tipe', 'error');
		}
	}
}
