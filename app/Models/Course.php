<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'name', 'color_id', 'active',
    ];

    public function coordinatorAt($date)
    {
        $coordinator = Coordinator::where('course_id', '=', $this->id)
            ->where(function ($query) use ($date) {
                $query->where('vigencia_fim', '=', null)
                    ->orWhere('vigencia_fim', '>=', $date);
            })
            ->get()->sortBy('id');

        if (sizeof($coordinator) > 0) {
            return $coordinator->last();
        }

        return null;
    }

    public function coordinator()
    {
        return $this->coordinatorAt(Carbon::today()->toDateString());
    }

    public function coordinators()
    {
        return $this->hasMany(Coordinator::class);
    }

    public function configurationAt($dateTime)
    {
        $config = CourseConfiguration::where('course_id', '=', $this->id)
            ->where('created_at', '<=', $dateTime)
            ->get()->sortBy('id');

        if (sizeof($config) > 0) {
            return $config->last();
        }

        return null;
    }

    public function configuration()
    {
        return $this->configurationAt(Carbon::now());
    }

    public function configurations()
    {
        return $this->hasMany(CourseConfiguration::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }
}
