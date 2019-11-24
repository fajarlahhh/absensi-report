<?php

namespace Absensi\Http\Controllers\Auth;

use Absensi\Pegawai;
use Absensi\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Absensi\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    public function showLoginForm(){
        return view('pages.login');
    }

    public function login(Request $request)
    {
        $remember = (Input::has('remember')) ? true : false;
        $attempt = Auth::attempt([
            'pengguna_id' => Input::get('uid'), 
            'password' => Input::get('password')], $remember);
        if ($attempt) {
            return Redirect::route('dashboard')
            ->with('judul', 'Selamat datang '.ucfirst(Auth::user()->pengguna_nama).'!')
            ->with('teks', 'Selamat bekerja dan semoga sukses')
            ->with('gambar', '../assets/img/user/user.png');
        }
        return Redirect::back()->withInput()->with('alert', 'ID Pengguna atau Kata Sandi salah');
    }

    private function username()
    {
        return 'pengguna_id';
    }
}
