<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScannerController extends Controller
{
    public function index()
    {
        $scannerId = session('scanner_id');
        return view('Scanner.index', compact('scannerId'));
    }

    public function logout(Request $request)
    {
        $request->session()->forget('scanner_id');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
