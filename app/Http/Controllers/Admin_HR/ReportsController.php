<?php

namespace App\Http\Controllers\Admin_HR;

use App\Http\Controllers\Controller;

class ReportsController extends Controller
{
    public function index()
    {
        return view('Admin_HR.pages.reports');
    }
}
