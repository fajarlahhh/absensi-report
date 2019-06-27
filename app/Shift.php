<?php

namespace Absensi;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    //
    protected $table = 'shift';
    protected $primaryKey = 'shift_id';
    public $incrementing = false;
    protected $timestamp = false;
}
