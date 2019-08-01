<?php

namespace Absensi;

use Illuminate\Database\Eloquent\Model;

class JenisIzin extends Model
{
    //
    protected $table = 'jenis_izin';
    protected $primaryKey = 'jenis_izin_keterangan';
    public $timestamps = false;
}
