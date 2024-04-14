<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;
    protected $fillable = ['display_order', 'arabic_title', 'kurdish_title', 'customer_id'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->customer_id = auth()->user()->id;
        });
    }
}
