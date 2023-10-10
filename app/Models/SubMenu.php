<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AclMenu;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
class SubMenu extends Model
{
    use SoftDeletes;
    
    protected $fillable = [ 'menu_id' , 'name',  'code',  'status', 'path' ,'order' ];

    public function menu(){
        return $this->belongsTo('App\Models\Menu','menu_id','id');

    }

    public function aclMenu(){
        return $this->hasMany('App\Models\AclMenu','sub_menu_id','id');
    }
    public function getActive($route) {
        $routeName = explode(".", $route);
        $submenu = $this;

        $status = false;
        if($submenu) {
            $routeSub = explode(".", $submenu->path);
            if(($routeSub[0] && $routeName[0]) && ($routeSub[0] == $routeName[0])) $status = true;
        }
        return $status;
    }
    
    protected $appends = ['statusName'];

    public function getStatusNameAttribute() {
        return $this->status == 1 ? "PUBLISHED" : "UNPUBLISHED";
    }
    
    public function isHasSubMenu() {
        $aclMenu = AclMenu::where('user_id', \Auth::user()->id)->where('menu_id', $this->menu_id)->where('sub_menu_id', $this->id)->first();
        return !empty($aclMenu);
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
            $relationMethods = ['aclMenu'];

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
