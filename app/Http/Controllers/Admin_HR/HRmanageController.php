<?php

namespace App\Http\Controllers\Admin_HR;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HRmanageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('/Admin_HR/HRmanage');
    }
}
