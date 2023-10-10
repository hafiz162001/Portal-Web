<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Tag extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'tag_articles';
    protected $fillable = [ 'name','image' ];
    public function articles()
    {
        return $this->hasMany(ArticleController::class);
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
