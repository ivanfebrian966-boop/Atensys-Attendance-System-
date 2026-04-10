<?php

namespace App\Http\Controllers\Super_Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SuperAdminController extends Controller
{
    public function index()
    {
        
        $user = Auth::user();
        return view('/Super_Admin/super_admin', compact('user'));
    }
}
