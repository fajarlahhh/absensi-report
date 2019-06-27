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
}
