<?php

namespace App\Models;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Banner extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'banners';
    protected $fillable = ['file',  'order', 'link_detail', 'category_id', 'type'];

    public function category()
    {
        return $this->belongsTo(CategoryArticle::class);
    }
    public static function getTypes()
    {
        return [
            [
                'text' => 'Blocx',
                'value' => 'blocx'
            ],
            [
                'text' => 'Dampak',
                'value' => 'dampak'
            ],
            [
                'text' => 'Gerak',
                'value' => 'gerak'
            ],
            [
                'text' => 'Semarak',
                'value' => 'semarak'
            ],
            [
                'text' => 'Ruang Ide',
                'value' => 'ruang_ide'
            ],
        ];
    }

    public function getType($type)
    {
        return Arr::first(self::getTypes(), function ($value, $key) use ($type) {
            return $value['value'] == $type;
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
