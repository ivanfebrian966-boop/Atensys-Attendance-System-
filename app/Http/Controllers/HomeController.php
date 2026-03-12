<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home() {
        $fungsional = [
            'scan QR code' => 'Scan QR code untuk menandakan kehadiran',
            'riwayat kehadiran' => 'Melihat riwayat kehadiran',
            'upload surat izin' => 'Upload surat izin untuk ketidakhadiran',
            'verifikasi surat izin' => 'Verifikasi surat izin oleh admin HR',
            'melihat status izin' => 'Melihat status izin yang diajukan',
            'kelola data absensi' => 'Admin HR dapat mengelola data absensi karyawan',
            'rekap kehadiran' => 'Admin HR dapat melihat rekap kehadiran karyawan',
            'export data kehadiran' => 'Admin HR dapat mengekspor data kehadiran dalam format Excel',
        ];
        return view('home', compact('fungsional'));
    }
}