<?php

namespace App\Models;

use App\Http\Controllers\TagController;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title',  'subtitle', 'publisher', 'category_id', 'category_name', 'highlight', 'category', 'type', 'url', 'descriptions'];

    public function galleries()
    {
        return $this->hasMany('App\Models\Gallery', 'parent_id', 'id')->where('type', 'article');
    }

    public function article_details()
    {
        return $this->hasMany('App\Models\ArticleDetail', 'articles_id', 'id')->orderBy('sort_num', 'ASC')->with('galleries');
    }

    public function linkRedirect()
    {
        return $this->hasOne('App\Models\LinkRedirect', 'ref_id', 'id')->withDefault();
    }

    public function article_detail()
    {
        return $this->belongsTo('App\Models\ArticleDetail', 'id', 'articles_id');
    }

    public function is_saved()
    {
        return $this->belongsTo('App\Models\Save', 'parent_id', 'id')->where('type', 'article')->where('category', 'evoria')->where('user_apps_id', auth()->user()->id);
    }

    public function is_liked()
    {
        return $this->belongsTo('App\Models\Like', 'id', 'parent_id')->where('user_apps_id', auth()->user()->id);
    }

    public function liked()
    {
        return $this->hasMany('App\Models\Like', 'parent_id', 'id');
    }

    public function comment()
    {
        return $this->hasMany('App\Models\Comment', 'parent_id', 'id');
    }

    public function views()
    {
        return $this->hasMany('App\Models\Views', 'parent_id', 'id')->where('type', 'article')->where('category', 'evoria');
    }

    public function category()
    {
        return $this->belongsTo(CategoryArticle::class);
    }
    public function channel()
    {
        return $this->belongsTo(ChannelArticle::class);
    }

    public function tags()
    {
       return $this->belongsToMany(TagController::class);
    }
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false , function($query, $search)
        {
            return $query->where('title', 'like', '%' . $search . '%')
                         ->orWhere('descriptions', 'like', '%' . $search . '%');
        });
    }
    // public static function getHighlights()
    // {
    //     return [
    //         [
    //             'text' => 'Unhighlighted',
    //             'value' => 0
    //         ],
    //         [
    //             'text' => 'Highlighted',
    //             'value' => 1
    //         ],
    //     ];
    // }

    // public static function getTypes()
    // {
    //     return [
    //         // [
    //         //     'text' => 'Article',
    //         //     'value' => 'article'
    //         // ],
    //         [
    //             'text' => 'Dampak',
    //             'value' => 'dampak'
    //         ],
    //         [
    //             'text' => 'Gerak',
    //             'value' => 'gerak'
    //         ],
    //         [
    //             'text' => 'Semarak',
    //             'value' => 'semarak'
    //         ],
    //         [
    //             'text' => 'Ruang Ide',
    //             'value' => 'ruang_ide'
    //         ],
    //     ];
    // }

    public static function getCategories()
    {
        return [
            [
                'text' => 'Blocx',
                'value' => 'blocx'
            ],
            [
                'text' => 'Picu Wujudkan',
                'value' => 'picu_wujudkan'
            ],
        ];
    }

    public function getHighlight($highlight)
    {
        return Arr::first(self::getHighlights(), function ($value, $key) use ($highlight) {
            return $value['value'] == $highlight;
        });
    }

    public function getType($type)
    {
        return Arr::first(self::getTypes(), function ($value, $key) use ($type) {
            return $value['value'] == $type;
        });
    }

    public function getCategory($category)
    {
        return Arr::first(self::getCategories(), function ($value, $key) use ($category) {
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
