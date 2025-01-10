<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryProperty extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'property_id',
        'display_order'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
    // handle display_order when creating record 
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->display_order = 1;
        });
    }
}
