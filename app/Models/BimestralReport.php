<?php

namespace App\Models;

use App\Models\Utils\Protocol;
use Carbon\Carbon;

/**
 * Model for bimestral_reports table.
 *
 * @package App\Models
 * @property int id
 * @property int internship_id
 * @property Carbon date
 * @property string protocol
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @property Internship internship
 * @property-read string formatted_protocol
 */
class BimestralReport extends Model
{
    use Protocol;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'internship_id', 'date', 'protocol',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'internship_id' => 'integer',

        'date' => 'date',
    ];

    public function internship()
    {
        return $this->belongsTo(Internship::class);
    }
}
