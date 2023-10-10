<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Laravolt\Indonesia\Models\Village;

class BlocLocation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name',  'indonesia_village_id', 'location_detail', 'location'];

    public function village()
    {
        return $this->belongsTo('Laravolt\Indonesia\Models\Village', 'indonesia_village_id', 'id');
    }

    public function beaconRelations()
    {
        return $this->hasMany('App\Models\BeaconRelation', 'parent_id', 'id')->where('parent', 'blocLocation');
    }

    public function beaconBlocs()
    {
        return $this->hasMany('App\Models\BeaconBloc');
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
