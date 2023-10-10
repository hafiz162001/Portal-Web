<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Visitor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [ 'title', 'name', 'phone', 'email', 'ktp' ];

    public function createdBy()
    {
        return $this->belongsTo('App\Models\UserApps', 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo('App\Models\UserApps', 'updated_by', 'id');
    }

    public function deletedBy()
    {
        return $this->belongsTo('App\Models\UserApps', 'deleted_by', 'id');
    }

    public function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => \Carbon\Carbon::parse($value)->toDateTimeString(),
        );
    }
    public function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => \Carbon\Carbon::parse($value)->toDateTimeString(),
        );
    }

    public static function getTitles()
    {
        return [
            [
                'text' => 'Tuan',
                'value' => 0
            ],
            [
                'text' => 'Nyonya',
                'value' => 1
            ],
            [
                'text' => 'Nona',
                'value' => 2
            ],
        ];
    }

    protected $appends = array('title_name');

    public function getTitleNameAttribute()
    {
        return collect(self::getTitles())->filter(function ($value, $key) {
            return $value["value"] == $this->title;
        })->first();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $user = Auth::user();
            if(Auth::user()){
                $model->created_by = $user->id;
            }
        });

        self::updating(function($model){
            $user = Auth::user();
            if(Auth::user()){
                $model->updated_by = $user->id;
            }
        });
        self::deleting(function($model) {
            $user = Auth::user();
            if(Auth::user()){
                $model->deleted_by = $user->id;
                $model->update();
            }
        });
    }
}
