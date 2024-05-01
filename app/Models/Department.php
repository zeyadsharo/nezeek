<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'display_order',
        'icon',
        'title_ar',
        'title_ku',
        'category_id',
    ];
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->customer_id = 1;
        });
    }
    public function category()
    {
        return $this->belongsTo(DepartmentCategory::class, 'category_id');
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}