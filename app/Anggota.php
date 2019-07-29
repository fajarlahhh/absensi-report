<?php

namespace Absensi;

use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    //
    protected $table = 'anggota';
    protected $primaryKey = 'anggota_id';
    public $incrementing = false;

    protected $fillable = [
        'pegawai_id', 'anggota_nip', 'anggotai_sandi', 'anggota_hak', 'anggota_kartu', 'anggota_pin'
    ];

    public function pegawai(){
        return $this->hasOne('Absensi\Pegawai', 'nip', 'anggota_nip')->select(['nip', 'nm_pegawai', 'kd_unit', 'kd_jabatan', 'kd_bagian'])->orderBy('nm_pegawai');
    }

    public function kantor(){
        return $this->hasOne('Absensi\Kantor', 'kantor_id', 'kantor_id');
    }

    public function fingerprint(){
        return $this->hasMany('Absensi\Fingerprint', 'pegawai_id', 'pegawai_id');
    }

    public function absen(){
        return $this->hasMany('Absensi\Absen', 'pegawai_id', 'pegawai_id')->orderBy('absen_tgl');
    }

    public function izin(){
        return $this->hasMany('Absensi\Izin', 'pegawai_id', 'pegawai_id')->orderBy('izin_tgl');
    }

    public function kehadiran(){
        return $this->hasMany('Absensi\Kehadiran', 'pegawai_id', 'pegawai_id')->orderBy('kehadiran_tgl');
    }
}
