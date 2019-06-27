<?php

namespace Absensi;

use Illuminate\Database\Eloquent\Model;

class ShiftKaryawan extends Model
{
    //
    protected $table = 'shift_karyawan';
    protected $primaryKey = 'shift_id';
    public $incrementing = false;
    protected $timestamp = false;

    public function shift()
    {
    	return $this->hasOne('Absensi\Shift', 'shift_id', 'shift_id');
    }

    public function anggota()
    {
    	return $this->hasOne('Absensi\Anggota', 'anggota_id', 'anggota_id');
    }
}
