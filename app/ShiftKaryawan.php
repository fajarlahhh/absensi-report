<?php

namespace Absensi;

use Illuminate\Database\Eloquent\Model;

class ShiftKaryawan extends Model
{
    //
    protected $table = 'shift_karyawan';
    protected $primaryKey = 'anggota_id';
    public $incrementing = false;
    public $timestamps = false;

    public function shift()
    {
    	return $this->hasOne('Absensi\Shift', 'shift_id', 'shift_id');
    }

    public function anggota()
    {
    	return $this->hasOne('Absensi\Anggota', 'anggota_id', 'anggota_id');
    }
}
