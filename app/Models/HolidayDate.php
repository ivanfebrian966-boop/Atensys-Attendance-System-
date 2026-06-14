<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon $date
 * @property string $name
 * @property string|null $description
 */
class HolidayDate extends Model
{
    use HasFactory;

    protected $table = 'holiday_dates';

    protected $primaryKey = 'holiday_id'; // migration uses id('holiday_id')

    protected $fillable = [
        'date',
        'name',
        'description',
    ];

    protected $casts = [
        'date'  => 'date',
    ];

    /**
     * Make $model->id transparently return the holiday_id value.
     * This is needed because the PK column is named 'holiday_id', not 'id'.
     */
    public function getIdAttribute(): ?int
    {
        return $this->holiday_id ?? null;
    }

}
