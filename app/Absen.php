<?php

namespace Absensi;

use Illuminate\Database\Eloquent\Model;

class Absen extends Model
{
    //
    protected $table = 'absen';
    protected $primaryKey = 'absen_id';
    public $incrementing = false;
    protected $fillable = [
        'pegawai_id',
        'absen_tgl',
        'absen_tgl_keterangan',
        'absen_hari',
        'absen_masuk_telat',
        'absen_masuk',
        'absen_masuk_keterangan',
        'absen_pulang',
        'absen_pulang_keterangan',
        'absen_lembur',
        'absen_lembur_keterangan',
        'absen_pulang_lembur',
        'absen_pulang_lembur_keterangan',
        'absen_istirahat',
        'absen_istirahat_keterangan',
        'absen_kembali',
        'absen_kembali_keterangan',
        'absen_izin',
        'absen_izin_keterangan'
        ];
    
    public function anggota()
    {
    	return $this->hasOne('Absensi\Anggota', 'pegawai_id', 'pegawai_id');
    }

    public function pegawai(){
        return $this->hasOne('Absensi\Pegawai', 'id', 'pegawai_id');
    }
}
