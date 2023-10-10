<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Xendit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [ 'external_id', 'payment_channel', 'status', 'email', 'price', 'request' ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function($model) {
        });

        self::updating(function($model){
        });
        self::deleting(function($model) {
        });
    }
}
