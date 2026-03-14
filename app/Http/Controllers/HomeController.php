<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        $features = [
            'QR Code Attendance' => 'Employees can check in and check out quickly by scanning a QR code provided by the company.',
            'Attendance History' => 'Employees can view their attendance history and monitor their attendance records easily.',
            'Employee & Division Management' => 'HR administrators can manage employee data and organize company divisions efficiently.',
            'Attendance Recap & Reports' => 'HR administrators can monitor attendance records and generate attendance recap reports.'
        ];
        return view('home', compact('features'));
    }
}
