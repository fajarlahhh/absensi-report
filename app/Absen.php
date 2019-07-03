<?php

namespace Absensi;

use Illuminate\Database\Eloquent\Model;

class Absen extends Model
{
    //
    protected $table = 'absen';
    protected $primaryKey = ['pegawai_id', 'absen_tgl'];
    public $incrementing = false;
    
    public function anggota()
    {
    	return $this->hasOne('Absensi\Anggota', 'pegawai_id', 'pegawai_id');
    }

    public function pegawai(){
        return $this->hasOne('Absensi\Pegawai', 'id', 'pegawai_id');
	}
}
