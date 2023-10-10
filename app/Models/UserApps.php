<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use \Carbon\Carbon;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class UserApps extends SanctumPersonalAccessToken
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'dob',
        'phone',
        'email',
        'gender',
        'isCheckin',
        'isRegistered',
        'role',
        'bloc_location_id',
        'active_event_id',
        'active_ticket_order_id',
        'title',
        'ktp',
        'foto',
        'foto_sampul',
        'isGuest',
        'fcm_token',
        'user_uuid',
        'user_category',
        'question1'
    ];

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

    public function activeTickets()
    {
        return $this->hasMany('App\Models\TicketOrder', 'user_id', 'id')->orderBy('id', 'desc')->where('status', 1)->whereDate('selected_date', Carbon::today());
    }

    public function activities()
    {
        return $this->hasMany('App\Models\UserActivity', 'user_id', 'id')->where('gues_id', 0)->orderBy('id', 'desc');
    }

    public function guesActivities()
    {
        return $this->hasMany('App\Models\UserActivity', 'gues_id', 'gues_id')->orderBy('id', 'desc');
    }

    public function event()
    {
        return $this->belongsTo('App\Models\Event', 'active_event_id', 'id');
    }

    public function ticket()
    {
        return $this->belongsTo('App\Models\TicketOrder', 'active_ticket_order_id', 'id');
    }

    public function blocLocation()
    {
        return $this->belongsTo('App\Models\BlocLocation');
    }

    public function contestan()
    {
        return $this->belongsTo('App\Models\Contestan', 'id', 'user_apps_id')->where('status', 1);
    }

    public function pendaftar()
    {
        return $this->belongsTo('App\Models\Contestan', 'id', 'user_apps_id')->where('status', 0);
    }
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

    public static function getRoles()
    {
        return [
            [
                'text' => 'Security',
                'value' => 0
            ],
            [
                'text' => 'Pengunjung',
                'value' => 1
            ],
        ];
    }

    public static function getGenders()
    {
        return [
            [
                'text' => 'Male',
                'value' => "male"
            ],
            [
                'text' => 'Female',
                'value' => "female"
            ],
        ];
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

    public static function getStatusRegistrations()
    {
        return [
            [
                'text' => 'False',
                'value' => 0
            ],
            [
                'text' => 'True',
                'value' => 1
            ],
        ];
    }

    public static function getStatusCheckins()
    {
        return [
            [
                'text' => 'False',
                'value' => 0
            ],
            [
                'text' => 'True',
                'value' => 1
            ],
        ];
    }

    public function getRole($role)
    {
        return Arr::first(self::getRoles(), function ($value, $key) use ($role) {
            return $value['value'] == $role;
        });
    }

    public function getStatusRegistration($isRegistered)
    {
        return Arr::first(self::getStatusRegistrations(), function ($value, $key) use ($isRegistered) {
            return $value['value'] == $isRegistered;
        });
    }
    public function getStatusCheckin($isCheckin)
    {
        return Arr::first(self::getStatusCheckins(), function ($value, $key) use ($isCheckin) {
            return $value['value'] == $isCheckin;
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
