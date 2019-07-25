<?php

namespace Absensi;

use Illuminate\Database\Eloquent\Model;

class Izin extends Model
{
    //
    protected $table = 'izin';
    protected $primaryKey = ['izin_id', 'izin_tgl'];
    public $incrementing = false;
    public function anggota()
    {
    	return $this->hasOne('Absensi\Anggota', 'pegawai_id', 'pegawai_id')->select(['nip', 'nm_pegawai', 'kd_unit', 'kd_jabatan', 'kd_bagian'])->orderBy('nm_pegawai');
    }

    public function pegawai(){
        return $this->hasOne('Absensi\Pegawai', 'id', 'pegawai_id');
	}
}
