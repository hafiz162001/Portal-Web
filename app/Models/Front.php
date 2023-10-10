<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Front extends Model
{
    use HasFactory;
    
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false , function($query, $search)
        {
            return $query->where('title', 'like', '%' . $search . '%')
                         ->orWhere('body', 'like', '%' . $search . '%')
                         ->orWhere('excerp', 'like', '%' . $search . '%');
        });
        $query->when($filters['channel_name'] ?? false, fn($query, $category) =>
            $query->whereHas('category', fn($query) =>
                $query->where('slug', $category)
            )   
        );
        $query->when($filters['category_name'] ?? false, fn($query, $user) =>
            $query->whereHas('user', fn($query) => 
                $query->where('username', $user)
            )
    );
    }
}
