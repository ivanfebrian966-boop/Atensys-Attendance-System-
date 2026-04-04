<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ProfileHRController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profileHR', compact('user'));
    }
}
