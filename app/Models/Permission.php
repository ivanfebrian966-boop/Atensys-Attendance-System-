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
        'status',
        'reject_reason',
        'information',
        'file',
        'start_date',
        'completion_date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'nip', 'nip');
    }
}
