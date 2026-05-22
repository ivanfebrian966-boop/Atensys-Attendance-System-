<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';
    protected $primaryKey = 'attendance_id';

    protected $fillable = [
        'nip',
        'check_in',
        'check_out',
        'attendance_status',
        'qr_code',
        'created_at',
        'updated_at',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'nip', 'nip');
    }

    /**
     * Mark past attendances with missing checkouts as Absent.
     */
    public static function syncMissingCheckouts()
    {
        self::whereNull('check_out')
            ->whereDate('check_in', '<', \Carbon\Carbon::today())
            ->whereIn('attendance_status', ['Present', 'Late'])
            ->update(['attendance_status' => 'Absent']);
    }
}
