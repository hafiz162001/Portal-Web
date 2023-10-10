<?php

namespace App\Models;

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BannerController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class CategoryArticle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [ 'name','image' ];
    public function articles()
    {
        return $this->hasMany(ArticleController::class);
    }
    public function banners()
    {
        return $this->hasMany(BannerController::class);
    }

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
