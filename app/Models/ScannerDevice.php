<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScannerDevice extends Model
{
    protected $table = 'scanner_devices';
    protected $primaryKey = 'scanner_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'scanner_id',
        'password',
    ];

    protected $hidden = [
        'password',
    ];
}
