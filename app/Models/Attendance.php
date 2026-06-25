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

        // Mark partial leaves as Absent if the end time has passed and they haven't checked in.
        $now = \Carbon\Carbon::now();
        $todayStr = \Carbon\Carbon::today()->toDateString();
        
        $partialPermissions = \App\Models\Permission::where('permission_status', 'Approved')
            ->whereNotNull('start_time')
            ->where(function($q) use ($todayStr) {
                $q->whereDate('start_date', '<', $todayStr)
                  ->orWhere(function($sq) use ($todayStr) {
                      $sq->whereDate('start_date', $todayStr)
                         ->whereTime('end_time', '<', \Carbon\Carbon::now()->toTimeString());
                  });
            })
            ->get();

        foreach ($partialPermissions as $perm) {
            $start = \Carbon\Carbon::parse($perm->start_date);
            $end = \Carbon\Carbon::parse($perm->completion_date);

            for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                if ($date->lt(\Carbon\Carbon::today()) || ($date->toDateString() == $todayStr && \Carbon\Carbon::now()->format('H:i:s') > $perm->end_time)) {
                    self::where('nip', $perm->nip)
                        ->whereDate('created_at', $date->toDateString())
                        ->whereNull('check_in')
                        ->where('attendance_status', 'Leave')
                        ->update(['attendance_status' => 'Absent']);
                }
            }
        }
    }
}
