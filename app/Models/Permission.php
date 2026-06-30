<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'permissions';
    protected $primaryKey = 'permission_id';

    protected $fillable = [
        'nip',
        'type',
        'leave_category',
        'sick_category',
        'permission_status',
        'reject_reason',
        'approval_reason',
        'information',
        'file',
        'start_date',
        'completion_date',
        'start_time',
        'end_time',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'nip', 'nip');
    }

    public static function autoApproveExpired()
    {
        $threshold = \Carbon\Carbon::now()->subDays(3);

        self::where('permission_status', 'Pending')
            ->where('created_at', '<=', $threshold)
            ->update([
                'permission_status' => 'Approved',
                'approval_reason' => 'Auto-approved by system (Exceeded 3 days limit)'
            ]);
    }
}
