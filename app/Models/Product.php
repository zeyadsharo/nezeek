<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
     protected $fillable = ['title', 'description', 'model', 'price', 'currency', 'cover_image', 'category_id', 'customer_id', 'display_order', 'display_to', 'auto_delete_at'];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

}
