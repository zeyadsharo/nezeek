<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Post extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'cover_image', 'post_date', 'display_order', 'content', 'customer_id', 'auto_delete_at'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->customer_id = auth()->user()->customer->id;
        });
        static::addGlobalScope('customer', function (Builder $builder) {
            $builder->where('customer_id', auth()->user()->customer->id);
        });
    }
}
