<?php

namespace App\Http\Controllers\Admin_HR;

use App\Http\Controllers\Controller;

class AttendanceController extends Controller
{
    public function index()
    {
        return view('/Admin_HR/attendance');
    }
}
