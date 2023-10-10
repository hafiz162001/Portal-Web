<?php

namespace App\Models;

use Illuminate\Support\Arr;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'fullname',
        'username',
        'role_code',
        'active',
        'bloc_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function accessmenu()
    {
        return $this->hasMany('App\Models\AclMenu', 'user_id', 'id');
    }
    public function role()
    {
        return  $this->belongsTo('App\Models\UserRole', 'role_code', 'code');
    }

    public function location()
    {
        return $this->belongsTo('App\Models\Location');
    }

    public function blocLocation()
    {
        return $this->belongsTo('App\Models\BlocLocation', 'bloc_id', 'id');
    }

    protected $appends = array('location_name', 'bloc_location_name');

    public function getLocationNameAttribute()
    {
        return !empty($this->location) ? $this->location->name . ' | ' . $this->location->type : "-";
    }


    public function getBlocLocationNameAttribute()
    {
        return !empty($this->blocLocation) ? $this->blocLocation->name : "-";
    }



    public function grantOption($role = null)
    {
        $grantOption['VIEW'] = 0;
        $grantOption['CREATE'] = 0;
        $grantOption['UPDATE'] = 0;
        $grantOption['DELETE'] = 0;
        $grantOption['SUPER'] = 0;
        if (isset($role)) {
            $grantOption['VIEW'] = $role->view;
            $grantOption['CREATE'] = $role->create;
            $grantOption['UPDATE'] = $role->update;
            $grantOption['DELETE'] = $role->delete;
            $grantOption['SUPER'] = $role->super;
        }
        return json_decode(json_encode($grantOption));
    }

    public static function getUserCategories()
    {
        return [
            [
                'text' => 'Blocx',
                'value' => "blocx"
            ],
            [
                'text' => 'Evoria',
                'value' => "evoria"
            ],
        ];
    }

    public function getUserCategory($category)
    {
        return Arr::first(self::getUserCategories(), function ($value, $key) use ($category) {
            return $value['value'] == $category;
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
