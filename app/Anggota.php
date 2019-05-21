<?php

namespace Absensi;

use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    //
    protected $table = 'm_anggota';
    protected $primaryKey = 'pegawai_id';
    public $incrementing = false;

    protected $fillable = [
        'pegawai_id', 'anggota_nip', 'anggotai_sandi', 'anggota_hak', 'anggota_kartu', 'anggota_pin'
    ];

    public function pegawai(){
        return $this->hasOne('Absensi\Pegawai', 'id', 'pegawai_id')->orderBy('nm_pegawai');
	}

	public function anggota_detail(){
		return $this->hasMany('Absensi\Anggotadetail');
	}
}
