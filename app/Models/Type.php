<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Type extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name'];

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
