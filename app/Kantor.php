<?php

namespace Absensi;

use Illuminate\Database\Eloquent\Model;

class Kantor extends Model
{
    //
    protected $table = 'kantor';
    protected $primaryKey = 'kantor_id';
    public $timestamps = false;

    public function unit(){
        return $this->hasOne('Absensi\Unit', 'kd_unit', 'unit_id');
	}
}
