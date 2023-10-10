<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
class Navigation extends Model
{
    use SoftDeletes;
    
    protected $fillable = [ 'name',  'code' ,'order' ];


    public function menu(){
        return $this->hasMany('App\Models\Menu','nav_id','id')->orderBy('order','asc');
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

        static::deleting(function($menu) {
            $relationMethods = ['menu'];

            foreach ($relationMethods as $relationMethod) {
                if ($menu->$relationMethod()->count() > 0) {
                    return false;
                }
            }
            $user = Auth::user();
            if(Auth::user()){
                $menu->deleted_by = $user->id;
                $menu->update();
            }
        });
    }
}
