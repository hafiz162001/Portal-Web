<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class UserActivity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['checkin_beacon_id', 'checkout_beacon_id', 'user_id', 'bloc_location_id', 'checkin_at', 'checkout_at', 'gues_id'];

    public function blocLocation()
    {
        return $this->belongsTo('App\Models\BlocLocation', 'bloc_location_id', 'id');
    }

    public function beaconCheckin()
    {
        return $this->belongsTo('App\Models\Beacon', 'checkin_beacon_id', 'id');
    }

    public function beaconCheckout()
    {
        return $this->belongsTo('App\Models\Beacon', 'checkout_beacon_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\UserApps');
    }

    public function scopeUserActivityEvoria(Builder $query, $from, $until)
    {
        return $query->distinct('user_id')->whereBetween('checkin_at', [$from, $until])->whereRelation('user', 'user_category', '=', 'evoria');
    }

    protected $appends = array('location_name', 'user_name', 'beacon_name_checkin', 'beacon_name_checkout');

    public function getCreatedDateAttribute()
    {
        return $this->created_at->toDateTimeString();
    }
    public function getUserNameAttribute()
    {
        return !empty($this->user) ? $this->user->name : "-";
    }
    public function getLocationNameAttribute()
    {
        // return $this->blocLocation;
        return !empty($this->blocLocation) ? $this->blocLocation->name : "-";
    }
    public function getBeaconNameCheckinAttribute()
    {
        return !empty($this->beaconCheckin) ? $this->beaconCheckin->name : "-";
    }
    public function getBeaconNameCheckoutAttribute()
    {
        // return $this->blocLocation;
        return !empty($this->beaconCheckout) ? $this->beaconCheckout->name : "-";
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $user = Auth::user();
            if (Auth::user()) {
                $model->created_by = $user->id;
            }
        });

        self::updating(function ($model) {
            $user = Auth::user();
            if (Auth::user()) {
                $model->updated_by = $user->id;
            }
        });
        self::deleting(function ($model) {
            $user = Auth::user();
            if (Auth::user()) {
                $model->deleted_by = $user->id;
                $model->update();
            }
        });
    }
}
