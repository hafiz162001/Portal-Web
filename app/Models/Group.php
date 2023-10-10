<?php

namespace App\Models;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'category', 'phone', 'description', 'axis', 'yaxis', 'ig', 'bloc_id', 'category_apps'];

    public function galleries()
    {
        return $this->hasMany('App\Models\Gallery', 'parent_id', 'id')->where('type', 'group');
    }

    // public function blocLocation()
    // {
    //     return $this->hasMany('App\Models\BlocLocation', 'id', 'bloc_id');
    // }

    public function blocLocation()
    {
        return $this->belongsTo('App\Models\BlocLocation', 'bloc_id', 'id');
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

    public function getCategoryCollabolators($categoryApps)
    {
        return Arr::first(self::getCategoryCollabolator(), function ($value, $key) use ($categoryApps) {
            return $value['value'] == $categoryApps;
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
