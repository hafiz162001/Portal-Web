<?php

namespace App\Models;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'type',  'bloc_location_id', 'phone', 'category_location_id', 'description', 'time_from', 'time_to', 'isRecommended', 'entrance', 'title', 'max_people', 'ig', 'axis', 'yaxis', 'category'];

    public function isLike()
    {
        return count($this->favorites) > 0;
    }

    public function favorites()
    {
        return $this->hasMany('App\Models\FavoriteTenant')->where('user_id', auth()->user()->id);
    }
    public function getEntrance()
    {
        return $this->entrance === 0 ? "Free" : "Ticketing";
    }
    public function blocLocation()
    {
        return $this->belongsTo('App\Models\BlocLocation', 'bloc_location_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\CategoryLocation', 'category_location_id', 'id');
    }

    public function beaconRelations()
    {
        return $this->hasMany('App\Models\BeaconRelation', 'parent_id', 'id')->where('parent', 'location');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    public function galleries()
    {
        return $this->hasMany('App\Models\Gallery', 'parent_id', 'id')->where('type', 'location');
    }

    public function galleries_venues()
    {
        return $this->hasMany('App\Models\Gallery', 'parent_id', 'id')->where('type', 'venues');
    }

    public function galleries_experience()
    {
        return $this->hasMany('App\Models\Gallery', 'parent_id', 'id')->where('type', 'experience');
    }

    public static function getCategoryCollabolator()
    {
        return [
            [
                'text' => 'Blocx',
                'value' => 'blocx'
            ],
            [
                'text' => 'X Collaborator',
                'value' => 'x_collaborator'
            ],
        ];
    }

    protected $appends = array('bloc_location_name');

    public function getBlocLocationNameAttribute()
    {
        return !empty($this->blocLocation) ? $this->blocLocation->name : "-";
    }

    public function getCategoryCollabolators($category)
    {
        return Arr::first(self::getCategoryCollabolator(), function ($value, $key) use ($category) {
            return $value['value'] == $category;
        });
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
