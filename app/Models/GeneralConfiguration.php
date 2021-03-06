<?php

namespace App\Models;

use Carbon\Carbon;

/**
 * Model for general_configurations table.
 *
 * @package App\Models
 * @property int id
 * @property int max_years
 * @property int min_year
 * @property int min_semester
 * @property int min_hours
 * @property int min_months
 * @property int min_months_ctps
 * @property float min_grade
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class GeneralConfiguration extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'max_years', 'min_year', 'min_semester', 'min_hours', 'min_months', 'min_months_ctps', 'min_grade',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'max_years' => 'integer',
        'min_year' => 'integer',
        'min_semester' => 'integer',
        'min_hours' => 'integer',
        'min_months' => 'integer',
        'min_months_ctps' => 'integer',
        'min_grade' => 'float',
    ];

    public static function getMaxYears($date)
    {
        $config = static::whereDate('created_at', '<=', $date)->orderBy('id')->get()->last();
        return $config->max_years;
    }
}
