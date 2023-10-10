<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ArticleDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['articles_id',  'description', 'caption', 'status', 'sort_num', 'url'];

    public function galleries()
    {
        return $this->hasMany('App\Models\Gallery', 'parent_id', 'id')->where('type', 'article_detail');
    }

    public function article()
    {
        return $this->belongsTo('App\Models\Article', 'articles_id', 'id');
    }

    public function getStatus()
    {
        return $this->status == 0 ? "Unpublished" : "Published";
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
