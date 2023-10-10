<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
class AclMenu extends Model
{
    use SoftDeletes;

    protected $fillable = [ 'user_id' ,'menu_id' ,'sub_menu_id' ];

    public function user(){
        return $this->belongsTo('App\Models\User');
	}

    protected static function boot()
    {
        parent::boot();

        self::creating(function($model){
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
        static::deleting(function($model) {
            $user = Auth::user();
            if(Auth::user()){
                $model->deleted_by = $user->id;
                $model->update();
            }
        });
    }
}
