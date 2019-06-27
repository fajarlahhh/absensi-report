<?php

namespace Absensi;

use Illuminate\Database\Eloquent\Model;

class Mesin extends Model
{    
    protected $table = 'mesin';
    protected $primaryKey = 'mesin_id';
    public $incrementing = false;

    public function kantor(){
        return $this->hasOne('Absensi\Kantor', 'kantor_id', 'kantor_id');
	}
}
