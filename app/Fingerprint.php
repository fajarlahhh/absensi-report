<?php

namespace Absensi;

use Illuminate\Database\Eloquent\Model;

class Fingerprint extends Model
{
    //
    protected $table = 'fingerprint';
    protected $primaryKey = ['pegawai_id', 'fingerprint_id'];
    public $incrementing = false;
}
