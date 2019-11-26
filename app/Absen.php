<?php

namespace Absensi;

use Illuminate\Database\Eloquent\Model;

class Absen extends Model
{
    //
    protected $table = 'absen';
    protected $primaryKey = ['pegawai_nip', 'absen_tanggal'];
    public $incrementing = false;
    protected $fillable = [
        'pegawai_nip',
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
    
    public function pegawai()
    {
    	return $this->belongsTo('Absensi\Pegawai', 'pegawai_nip', 'pegawai_nip');
    }
}
