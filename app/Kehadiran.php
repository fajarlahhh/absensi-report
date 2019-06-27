<?php

namespace Absensi;

use Illuminate\Database\Eloquent\Model;

class Kehadiran extends Model
{
    //
    protected $table = 'kehadiran';
    protected $primaryKey = 'kehadiran_id';
    public $incrementing = false;

    public function anggota()
    {
    	return $this->hasOne('Absensi\Anggota', 'pegawai_id', 'pegawai_id');
    }

    public function pegawai(){
        return $this->hasOne('Absensi\Pegawai', 'id', 'pegawai_id');
	}
}
