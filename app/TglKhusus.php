<?php

namespace Absensi;

use Illuminate\Database\Eloquent\Model;

class TglKhusus extends Model
{
    //
    protected $table = 'tgl_khusus';
    public $incrementing = false;
    protected $timestamp = false;
    protected $primaryKey = 'tgl_khusus_waktu';
}
