<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
class UserRole extends Model
{
    use SoftDeletes;
    
    protected $table = 'user_role';
 
    protected $fillable = ['id','code','view','create','update','delete'];

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
            $model->code = strtoupper($model->code);
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
