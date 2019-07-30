<?php

namespace Absensi;

use Illuminate\Database\Eloquent\Model;

class ShiftKaryawan extends Model
{
    //
    protected $table = 'shift_karyawan';
    protected $primaryKey = 'pegawai_id';
    public $incrementing = false;
    public $timestamps = false;

    public function shift()
    {
    	return $this->hasOne('Absensi\Shift', 'shift_id', 'shift_id');
    }

    public function anggota()
    {
    	return $this->belongsTo('Absensi\Anggota', 'pegawai_id', 'pegawai_id');
    }
}
