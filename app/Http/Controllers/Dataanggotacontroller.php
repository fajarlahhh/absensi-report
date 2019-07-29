<?php

namespace Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Absensi\Anggota;
use Absensi\Fingerprint;
use Absensi\Pegawai;
use Absensi\Mesin;
use Absensi\Unit;
use Absensi\Kantor;
use Illuminate\Support\Facades\DB;

class Dataanggotacontroller extends Controller
{
    public function index(Request $req)
    {
		$kantor = Kantor::all();
		$kantor_id = $req->kantor? $req->kantor: $kantor{0}->kantor_id;
		$anggota = Anggota::with(['pegawai' => function($q) {
			$q->with('unit');
			$q->with('bagian');
			$q->with('jabatan');
		}])->whereHas('pegawai', function($q) use ($req){
			$q->where('nm_pegawai', 'like', '%'.$req->cari.'%');
			$q->orWhere('nip', 'like', '%'.$req->cari.'%');
		})->with('kantor')
		->where('anggota.kantor_id', $kantor_id)->paginate(10);
		$anggota->appends(['kantor' => $kantor_id, 'cari' => $req->cari])->links();
		return view('pages.master.dataanggota.index',[
			'kantor' => $kantor,
			'kantor_id' => $kantor_id,
			'data' => $anggota,
			'cari' => $req->cari
		]);
    }

    public function tambah()
	{
		$kantor = Kantor::all();
		$pegawai = Pegawai::select('id', 'nm_pegawai', 'nip')
		->orderBy('nm_pegawai', 'asc')
		->where('kd_status', '!=', '07')
		->get();
		return view('pages.master.dataanggota.form',[
			'data' => null,
			'aksi' => 'Tambah',
			'pegawai' => $pegawai,
			'kantor' => $kantor
		]);
	}

	public function do_tambah(Request $req)
	{
		$req->validate(
			[
				'pegawai_id' => 'required',
				'anggota_hak_akses' => 'required',
				'kantor_id' => 'required'
			],[
         	   'pegawai_id.required' => 'Pegawai tidak boleh kosong',
         	   'anggota_hak_akses.required' => 'Hak Akses tidak boleh kosong',
         	   'kantor_id.required' => 'Kantor tidak boleh kosong'
        	]
		);
		try{
			if (Anggota::where('pegawai_id', $req->get('pegawai_id'))->where('kantor_id', $req->get('kantor_id'))->first() !== null) {
				return redirect('dataanggota/tambah')
						->with('pesan', 'Anggota sudah ada')
						->with('judul', 'Tambah data')
						->with('tipe', 'error');
			}else{
				$mesin = Mesin::where('kantor_id', $req->kantor_id)->get();
				$buffer = [];
				$response = [];
				$i = 0;
				foreach ($mesin as $key => $msn) {
					$Connect = fsockopen($msn->mesin_ip, "80", $errno, $errstr, 1);
					if($Connect){
						$soap_request="<SetUserInfo><ArgComKey Xsi:type=\"xsd:integer\">".$msn->mesin_key."</ArgComKey><Arg><PIN>".$req->get('pegawai_id')."</PIN><Name>".$req->get('anggota_nip')."</Name><Password>".$req->get('anggota_sandi')."</Password><Privilege>".$req->get('anggota_hak_akses')."</Privilege></Arg></SetUserInfo>";
						$newLine="\r\n";
						fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
					    fputs($Connect, "Content-Type: text/xml".$newLine);
					    fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
					    fputs($Connect, $soap_request.$newLine);
						$buffer[$i]="";
						while($response[$i]=fgets($Connect, 1024)){
							$buffer[$i]=$buffer[$i].$response[$i];
						}
					}
					$Connect = fsockopen($msn->mesin_ip, "80", $errno, $errstr, 1);
					if($Connect){
						$fingerprint = Fingerprint::where('pegawai_id', $req->get('pegawai_id'))->get();
						foreach ($fingerprint as $key => $fgr) {
							$soap_request="<SetUserTemplate><ArgComKey xsi:type=\"xsd:integer\">".$msn->mesin_key."</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">".$req->get('pegawai_id')."</PIN><FingerID xsi:type=\"xsd:integer\">".$fgr->fingerprint_id."</FingerID><Size>".strlen($fgr->fingerprint_template)."</Size><Valid>1</Valid><Template>".$fgr->fingerprint_template."</Template></Arg></SetUserTemplate>";
							$newLine="\r\n";
							fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
						    fputs($Connect, "Content-Type: text/xml".$newLine);
						    fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
						    fputs($Connect, $soap_request.$newLine);
							$buffer[$i]="";
							while($response[$i]=fgets($Connect, 1024)){
								$buffer[$i]=$buffer[$i].$response[$i];
							}
						}
					}

					$i++;
				}
				$anggota = new Anggota();
				$anggota->anggota_nip = $req->get('anggota_nip');
				$anggota->pegawai_id = $req->get('pegawai_id');
				$anggota->kantor_id = $req->get('kantor_id');
				$anggota->anggota_sandi = $req->get('anggota_sandi');
				$anggota->anggota_hak_akses = $req->get('anggota_hak_akses');
				$anggota->save();

				return redirect('dataanggota')
				->with('pesan', 'Berhasil menambah data anggota (NIP:'.$req->get('anggota_nip').')')
				->with('judul', 'Tambah data')
				->with('tipe', 'success');
			}
		}catch(\Exception $e){
			return redirect($req->get('redirect'))
			->with('pesan', 'Gagal menambah data anggota (NIP:'.$req->get('anggota_nip').') Error: '.$e->getMessage())
			->with('judul', 'Tambah data')
			->with('tipe', 'error');
		}
	}

	public function hapus($id)
	{
		try{
			$anggota = Anggota::findorfail($id);
			$mesin = Mesin::where('kantor_id', $anggota->kantor_id)->get();
			if(count($mesin) > 0){
				$buffer = [];
				$response = [];
				$i = 0;
				foreach ($mesin as $key => $msn) {
					$Connect = fsockopen($msn->mesin_ip, "80", $errno, $errstr, 1);
					if($Connect){
						$soap_request="<DeleteUser><ArgComKey xsi:type=\"xsd:integer\">".$msn->mesin_key."</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">".$anggota->pegawai_id."</PIN></Arg></DeleteUser>";
						$newLine="\r\n";
						fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
					    fputs($Connect, "Content-Type: text/xml".$newLine);
					    fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
					    fputs($Connect, $soap_request.$newLine);
						$buffer[$i]="";
						while($Response[$i]=fgets($Connect, 1024)){
							$buffer[$i]=$buffer[$i].$Response[$i];
						}
						
						$anggota->delete();
					}
				}
				return redirect()->back()
				->with('pesan', 'Berhasil menghapus data anggota (NIP:'.$anggota->anggota_nip.')')
				->with('judul', 'Hapus data')
				->with('tipe', 'success');
			}else{
				return redirect()->back()
				->with('pesan', 'Gagal menghapus data anggota. Data mesin tidak tersedia untuk kantor ini')
				->with('judul', 'Upload data')
				->with('tipe', 'error');
			}
		}catch(\Exception $e){
			return redirect()->back()
			->with('pesan', 'Gagal menghapus data anggota (NIP:'.$id.') Error: '.$e->getMessage())
			->with('judul', 'Hapus data')
			->with('tipe', 'error');
		}
	}

	public function download(Request $req)
	{
		try{
			$mesin = Mesin::where('kantor_id', $req->kantor_id)->get();
			if(count($mesin) > 0){
				$anggota = Anggota::where('kantor_id', $req->kantor_id)->get();
				$template = [];
				$i = 0;

				foreach ($mesin as $key => $msn) {
					foreach ($anggota as $key => $angg) {
						$buffer="";
						$Connect = fsockopen($msn->mesin_ip, "80", $errno, $errstr, 1);
						if($Connect){
							$soap_request="<GetUserTemplate><ArgComKey xsi:type=\"xsd:integer\">".$msn->mesin_key."</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">".$angg->pegawai_id."</PIN></Arg></GetUserTemplate>";
							$newLine="\r\n";
							fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
						    fputs($Connect, "Content-Type: text/xml".$newLine);
						    fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
						    fputs($Connect, $soap_request.$newLine);
							while($Response=fgets($Connect, 1024)){
								$buffer=$buffer.$Response;
							}
						}
						$buffer=$this->parse($buffer,"<GetUserTemplateResponse>","</GetUserTemplateResponse>");
						$buffer=explode("\r\n",$buffer);
						$template[$i] = $buffer;

						$i++;
					}
					$data = [];
					for($a = 0; $a < count($template); $a++){
						if((int)$this->parse($template[$a][1],"<PIN>","</PIN>") != 0){
							Fingerprint::where('pegawai_id', (int)$this->parse($template[$a][1],"<PIN>","</PIN>"))->delete();
							Fingerprint::insert(array(
								'pegawai_id' => (int)$this->parse($template[$a][1],"<PIN>","</PIN>"),
								'fingerprint_id' => (int)$this->parse($template[$a][1],"<FingerID>","</FingerID>"),
								'fingerprint_size' => (int)$this->parse($template[$a][1],"<Size>","</Size>"),
								'fingerprint_valid' => (int)$this->parse($template[$a][1],"<Valid>","</Valid>"),
								'fingerprint_template' => $this->parse($template[$a][1],"<Template>","</Template>")
							));
						}						
					}
				}
				return redirect()->back()
				->with('pesan', 'Berhasil mendownload data fingerprint')
				->with('judul', 'Download Fingerprint')
				->with('tipe', 'success');
			}else{
				return redirect()->back()
				->with('pesan', 'Gagal mendownload data fingerprint. Data mesin tidak tersedia untuk kantor ini')
				->with('judul', 'Download Fingerprint')
				->with('tipe', 'error');
			}
		}catch(\Exception $e){
			return redirect()->back()
			->with('pesan', 'Gagal mendownload data fingerprint. Error: '.$e->getMessage())
			->with('judul', 'Download Fingerprint')
			->with('tipe', 'error');
		}
	}

    public function upload_anggota()
	{
		$mesin = Mesin::all();
		return view('pages.master.dataanggota.upload',[
			'data' => null,
			'mesin' => $mesin
		]);
	}

	public function do_upload_anggota(Request $req)
	{
		try{
			$mesin = Mesin::where('mesin_id', $req->get('mesin_id'))->get();
			$buffer = [];
			$response = [];
			$i = 0;
			foreach ($mesin as $key => $msn) {
				$anggota = Anggota::where('kantor_id', $msn->kantor_id)->get();
				foreach ($anggota as $key => $angg) {
					$Connect = fsockopen($msn->mesin_ip, "80", $errno, $errstr, 1);
					if($Connect){
						$soap_request="<SetUserInfo><ArgComKey Xsi:type=\"xsd:integer\">".$msn->mesin_key."</ArgComKey><Arg><PIN>".$angg->pegawai_id."</PIN><Name>".$angg->anggota_nip."</Name><Password>".$angg->anggota_sandi."</Password><Privilege>".$angg->anggota_hak_akses."</Privilege></Arg></SetUserInfo>";
						$newLine="\r\n";
						fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
					    fputs($Connect, "Content-Type: text/xml".$newLine);
					    fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
					    fputs($Connect, $soap_request.$newLine);
						$buffer[$i]="";
						while($response[$i]=fgets($Connect, 1024)){
							$buffer[$i]=$buffer[$i].$response[$i];
						}
					}
					$Connect1 = fsockopen($msn->mesin_ip, "80", $errno, $errstr, 1);
					if($Connect1){
						$fingerprint = Fingerprint::where('pegawai_id', $angg->pegawai_id)->get();
						foreach ($fingerprint as $key => $fgr) {
							$soap_request="<SetUserTemplate><ArgComKey xsi:type=\"xsd:integer\">".$msn->mesin_key."</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">".$angg->pegawai_id."</PIN><FingerID xsi:type=\"xsd:integer\">".$fgr->fingerprint_id."</FingerID><Size>".strlen($fgr->fingerprint_template)."</Size><Valid>1</Valid><Template>".$fgr->fingerprint_template."</Template></Arg></SetUserTemplate>";
							$newLine="\r\n";
							fputs($Connect1, "POST /iWsService HTTP/1.0".$newLine);
						    fputs($Connect1, "Content-Type: text/xml".$newLine);
						    fputs($Connect1, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
						    fputs($Connect1, $soap_request.$newLine);
							$buffer[$i]="";
							while($response[$i]=fgets($Connect1, 1024)){
								$buffer[$i]=$buffer[$i].$response[$i];
							}
						}
					}

					$i++;
				}
			}

			return redirect('dataanggota')
			->with('pesan', 'Berhasil mengupload data anggota')
			->with('judul', 'Tambah data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect($req->get('redirect'))
			->with('pesan', 'Gagal mengupload data anggota. Error: '.$e->getMessage())
			->with('judul', 'Tambah data')
			->with('tipe', 'error');
		}
	}

	public function upload(Request $req)
	{
		try{
			$buffer = [];
			$response = [];
			$i = 0;
			$anggota = Anggota::where('kantor_id', $req->kantor_id)->get();
			foreach ($anggota as $key => $angg) {
				$mesin = Mesin::where('kantor_id', $req->kantor_id)->get();
				foreach ($mesin as $key => $msn) {
					$Connect = fsockopen($msn->mesin_ip, "80", $errno, $errstr, 1);
					if($Connect){
						$fingerprint = Fingerprint::where('pegawai_id', $angg->pegawai_id)->get();
						foreach ($fingerprint as $key => $fgr) {
							$soap_request="<SetUserTemplate><ArgComKey xsi:type=\"xsd:integer\">".$msn->mesin_key."</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">".$angg->pegawai_id."</PIN><FingerID xsi:type=\"xsd:integer\">".$fgr->fingerprint_id."</FingerID><Size>".strlen($fgr->fingerprint_template)."</Size><Valid>1</Valid><Template>".$fgr->fingerprint_template."</Template></Arg></SetUserTemplate>";
							$newLine="\r\n";
							fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
						    fputs($Connect, "Content-Type: text/xml".$newLine);
						    fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
						    fputs($Connect, $soap_request.$newLine);
							$buffer[$i]="";
							while($response[$i]=fgets($Connect, 1024)){
								$buffer[$i]=$buffer[$i].$response[$i];
							}
							$i++;
						}
					}
				}
			}

			return redirect('dataanggota')
			->with('pesan', 'Berhasil mengupload data fingerprint')
			->with('judul', 'Tambah data')
			->with('tipe', 'success');
		}catch(\Exception $e){
			return redirect()->back()
			->with('pesan', 'Gagal mendownload data fingerprint. Error: '.$e->getMessage())
			->with('judul', 'Download Fingerprint')
			->with('tipe', 'error');
		}
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
}
