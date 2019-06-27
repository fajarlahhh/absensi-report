<?php

namespace Absensi;

use Illuminate\Database\Eloquent\Model;

class Aturan extends Model
{
    protected $table = 'aturan';
    protected $primaryKey = 'aturan_id';
    public $incrementing = false;
    public $timestamps = false;
}
