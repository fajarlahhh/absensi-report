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
    protected $table = 'm_pengguna';
    protected $primaryKey = 'pengguna_nip';
    public $incrementing = false;
    protected $rememberTokenName = 'pengingat';

    protected $fillable = [
        'pengguna_nip', 'pengguna_sandi'
    ];

    public function getAuthPassword()
    {
        return $this->pengguna_sandi;
    }

    public function pegawai(){
        return $this->hasOne('Absensi\Pegawai', 'nip', 'pengguna_nip')->orderBy('nm_pegawai');
	}

    public static $admin = ['201604331'];
}
