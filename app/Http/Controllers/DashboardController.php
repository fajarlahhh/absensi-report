<?php

namespace Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DashboardController extends Controller
{
    //
	public function __construct()
	{
		$this->middleware('auth');
	}

    public function index()
    {
		return view('pages.dashboard.index');
    }
}
