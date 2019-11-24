<?php

namespace Absensi;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';
    protected $primaryKey = ['pegawai_nip', 'kantor_id'];
    public $incrementing = false;
    public $timestamps = false;

    public function kantor(){
    	return $this->hasOne('Absensi\Kantor', 'kantor_id', 'kantor_id');
    }
    
    public function absen(){
    	return $this->hasMany('Absensi\Absen', 'pegawai_nip', 'pegawai_nip');
	}
}
