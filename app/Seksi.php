<?php

namespace Absensi;

use Illuminate\Database\Eloquent\Model;

class Seksi extends Model
{
    protected $table = 'personalia.seksi';
    protected $primaryKey = 'kd_seksi';
    public $incrementing = false;

    public function bagian()
    {
    	return $this->belongsTo('Absensi\Bagian', 'kd_bagian', 'kd_bagian');
    }
}
