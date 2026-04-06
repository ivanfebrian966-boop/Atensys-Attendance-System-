<?php

namespace App\Http\Controllers\Admin_HR;

use function Symfony\Component\String\u;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileHRController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('/Admin_HR/profileHR', compact('user'));
    }
}
