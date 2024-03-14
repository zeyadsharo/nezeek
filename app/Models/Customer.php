<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
     protected $fillable = [
    'arabic_title', 
     'kurdish_title', 
     'description', 
     'about',
      'contact_info', 
      'display_order',
       'slug', 
       'activation_state', 
       'logo', 
       'latitude', 
       'longitude', 
       'next_payment', 
       'area_id',
       'sector_id'];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    public function productCategories()
    {
        return $this->hasMany(ProductCategory::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
