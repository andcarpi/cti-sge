<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function coordinators()
    {
        return $this->hasMany(Coordinator::class)
            ->WhereDate('end_date', '>', Carbon::today()->toDateString())
            ->orWhereNull('end_date')->where('user_id', '=', $this->id);
    }

    public function isCoordinator($temp = true)
    {
        if ($temp) {
            return sizeof($this->coordinators) > 0;
        } else {
            return sizeof($this->coordinators->where('temp_of', '<>', null)) > 0;
        }
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isCompany()
    {
        return $this->hasRole('company');
    }

    public function company()
    {
        if ($this->isCompany()) {
            return $this->belongsTo(Company::class, 'email', 'email');
        }

        return null;
    }

    public function getCoordinatorOfAttribute()
    {
        return $this->coordinators()->groupBy('course_id')->get('course_id')->map(function ($c) {
            return $c->course;
        });
    }

    public function getNonTempCoordinatorOfAttribute()
    {
        return $this->coordinators()->where('temp_of', '<>', null)->groupBy('course_id')->get('course_id')->map(function ($c) {
            return $c->course;
        });
    }

    public function getCoordinatorCoursesIdAttribute()
    {
        return Auth::user()->coordinator_of->map(function ($course) {
            return $course->id;
        })->toArray();
    }

    public function getNonTempCoordinatorCoursesIdAttribute()
    {
        return Auth::user()->non_temp_coordinator_of->map(function ($course) {
            return $course->id;
        })->toArray();
    }

    public function getCoordinatorCoursesNameAttribute()
    {
        $array = $this->non_temp_coordinator_of->map(function ($c) {
            return $c->name;
        })->toArray();

        $last = array_slice($array, -1);
        $first = join(', ', array_slice($array, 0, -1));
        $both = array_filter(array_merge([$first], $last), 'strlen');
        return join(' e ', $both);
    }

    public function getFormattedPhoneAttribute()
    {
        $phone = $this->phone;
        $ddd = substr($phone, 0, 2);
        $p1 = (strlen($phone) == 10) ? substr($phone, 2, 4) : substr($phone, 2, 5);
        $p2 = (strlen($phone) == 10) ? substr($phone, 6, 4) : substr($phone, 7, 4);
        return "($ddd) $p1-$p2";
    }
}
