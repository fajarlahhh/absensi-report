<?php

namespace Absensi;

use Illuminate\Database\Eloquent\Model;

class Kantor extends Model
{
    //
    protected $table = 'kantor';
    protected $primaryKey = 'kantor_id';
    public $timestamps = false;

    public function kantor(){
    	return $this->hasMany('Absensi\Pegawai', 'kantor_id', 'kantor_id');
	}
}
