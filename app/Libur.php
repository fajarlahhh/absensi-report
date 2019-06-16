<?php

namespace Absensi;

use Illuminate\Database\Eloquent\Model;

class Libur extends Model
{
    //
    protected $table = 'm_libur';
    protected $timestamp = false;
    public $incrementing = false;
    protected $primaryKey = 'libur_tgl';
}
