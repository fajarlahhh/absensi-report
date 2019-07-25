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
        return $this->hasOne('Absensi\Pegawai', 'nip', 'pengguna_nip')->select(['nip', 'nm_pegawai', 'kd_unit', 'kd_jabatan', 'kd_bagian'])->orderBy('nm_pegawai');
	}

    public static $admin = ['201604331'];
}
