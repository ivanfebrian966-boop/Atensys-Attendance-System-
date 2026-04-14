<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'type',
        'start_date',
        'end_date',
        'information',
        'status',
        'attachment',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
