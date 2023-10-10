<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Gallery extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['image', 'type', 'parent_id'];

    public function parent()
    {
        $parent = 'App\Models\Location';
        return $this->belongsTo($parent, 'parent_id', 'id');
    }

    public function getBase64Image()
    {
        return img_enc_base64(public_path('/img/' . $this->image));
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
