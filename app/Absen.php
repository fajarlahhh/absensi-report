<?php

namespace Absensi;

use Illuminate\Database\Eloquent\Model;

class Absen extends Model
{
    //
    protected $table = 'absen';
    protected $primaryKey = ['pegawai_id', 'absen_tanggal'];
    public $incrementing = false;
    protected $fillable = [
        'pegawai_id',
        'absen_tanggal',
        'absen_hari',
        'absen_izin',
        'absen_telat',
        'absen_masuk',
        'absen_pulang',
        'absen_istirahat',
        'absen_kembali',
        'absen_lembur',
        'absen_lembur_pulang'
        ];
    
    public function anggota()
    {
    	return $this->belongsTo('Absensi\Anggota', 'pegawai_id', 'pegawai_id');
    }
}
