<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon $date
 * @property array|null $names
 * @property string|null $description
 * @property-read string $name
 * @property-read string $names_label
 */
class HolidayDate extends Model
{
    use HasFactory;

    protected $table = 'holiday_dates';

    protected $fillable = [
        'date',
        'names',       // JSON array — supports multiple holidays per date
        'description',
    ];

    protected $casts = [
        'date'  => 'date',
        'names' => 'array',   // auto encode/decode JSON
    ];

    /**
     * Get the first name (for backward compatibility).
     */
    public function getNameAttribute(): string
    {
        $names = $this->names;

        if (is_array($names) && count($names) > 0) {
            $first = reset($names);
            return (string) $first;
        }

        return '';
    }

    /**
     * Combine all names into one string (for display).
     */
    public function getNamesLabelAttribute(): string
    {
        return implode(' & ', $this->names ?? []);
    }
}
