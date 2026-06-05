<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HolidayDate extends Model
{
    use HasFactory;

    protected $table = 'holiday_dates';

    protected $fillable = [
        'date',
        'names',       // JSON array — mendukung lebih dari 1 nama hari libur per tanggal
        'description',
    ];

    protected $casts = [
        'date'  => 'date',
        'names' => 'array',   // auto encode/decode JSON
    ];

    /**
     * Ambil nama pertama (untuk kompatibilitas backward).
     */
    public function getNameAttribute(): string
    {
        $names = $this->names ?? [];
        return !empty($names) ? $names[0] : '';
    }

    /**
     * Gabungkan semua nama menjadi satu string (untuk display).
     */
    public function getNamesLabelAttribute(): string
    {
        return implode(' & ', $this->names ?? []);
    }
}
