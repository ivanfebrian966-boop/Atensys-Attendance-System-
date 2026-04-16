<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'employees';
    protected $primaryKey = 'nip';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nip',
        'password',
        'name',
        'role',
        'position',
        'email',
        'division_id',
        'no_hp',
        'alamat',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id', 'division_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'nip', 'nip');
    }

    public function getPhoneAttribute()
    {
        return $this->no_hp;
    }

    public function getAddressAttribute()
    {
        return $this->alamat;
    }

    public function getDivisionNameAttribute()
    {
        return $this->division ? $this->division->division_name : 'N/A';
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'nip', 'nip');
    }
}
