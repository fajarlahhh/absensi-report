<?php

namespace Absensi;

use Illuminate\Database\Eloquent\Model;

class Mesin extends Model
{    
    protected $table = 'm_mesin';
    protected $primaryKey = 'mesin_id';
    public $incrementing = false;

    public function unit(){
        return $this->hasOne('Absensi\Unit', 'kd_unit', 'unit_kd');
	}
}
