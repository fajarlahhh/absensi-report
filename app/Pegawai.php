<?php

namespace Absensi;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'personalia.pegawai';
    protected $primaryKey = ['id', 'nip'];
    public $incrementing = false;


    public function pengguna(){
    	return $this->hasOne('Absensi\Pengguna', 'pengguna_nip', 'nip');
	}

	public function jabatan()
	{
		return $this->hasOne('Absensi\Jabatan', 'kd_jabatan', 'kd_jabatan');
	}

	public function unit()
	{
		return $this->hasOne('Absensi\Unit', 'kd_unit', 'kd_unit');
	}

	public function bagian()
	{
		return $this->hasOne('Absensi\Bagian', 'kd_bagian', 'kd_bagian');
	}

	public function seksi()
	{
		return $this->hasOne('Absensi\Seksi', 'kd_seksi', 'kd_seksi');
	}
}
