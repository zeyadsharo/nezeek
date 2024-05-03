<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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
            $model->customer_id = 1;
        });
        static::addGlobalScope('customer', function (Builder $builder) {
            $builder->where('customer_id', auth()->user()->customer_id);
        });
    }
}
