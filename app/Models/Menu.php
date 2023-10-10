<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AclMenu;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Menu extends Model
{
    use SoftDeletes;

    protected $fillable = ['name',  'code',  'status', 'nav_id', 'icon', 'order'];

    public function submenu()
    {
        return $this->hasMany('App\Models\SubMenu', 'menu_id', 'id')->orderBy('order', 'asc');
    }

    public function aclMenu()
    {
        return $this->hasMany('App\Models\AclMenu', 'menu_id', 'id');
    }
    public function navi()
    {
        return $this->belongsTo('App\Models\Navigation', 'nav_id', 'id');
    }

    public function getActive($route)
    {
        $routeName = explode(".", $route);
        $submenu = $this->submenu()->where('path', "LIKE", "%" . $routeName[0] . "%")->first();

        $status = false;
        if ($submenu) {
            $routeSub = explode(".", $submenu->path);
            if (($routeSub[0] && $routeName[0]) && ($routeSub[0] == $routeName[0])) $status = true;
        }
        return $status;
    }

    protected $appends = ['statusName'];

    public function getStatusNameAttribute()
    {
        return $this->status == 1 ? "PUBLISHED" : "UNPUBLISHED";
    }

    public function isHasMenu()
    {
        $aclMenu = AclMenu::where('user_id', \Auth::user()->id)->where('menu_id', $this->id)->get();
        return count($aclMenu) > 0;
    }

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
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
        static::deleting(function ($menu) {
            $relationMethods = ['submenu', 'aclMenu'];

            foreach ($relationMethods as $relationMethod) {
                if ($menu->$relationMethod()->count() > 0) {
                    return false;
                }
            }
            $user = Auth::user();
            if (Auth::user()) {
                $menu->deleted_by = $user->id;
                $menu->update();
            }
        });
    }
}
