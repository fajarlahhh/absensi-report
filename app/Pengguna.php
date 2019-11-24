<?php

namespace Absensi;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class Pengguna extends Authenticatable
{

    use Notifiable;
    use HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'pengguna';
    protected $primaryKey = 'pengguna_id';
    public $incrementing = false;
    protected $rememberTokenName = 'pengingat';
    public $timestamps = false;

    protected $fillable = [
        'pengguna_id', 'pengguna_sandi'
    ];

    public function getAuthPassword()
    {
        return $this->pengguna_sandi;
    }
}
