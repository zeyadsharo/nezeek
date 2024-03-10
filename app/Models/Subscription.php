<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;
     protected $fillable = ['customer_id', 'feature_id', 'price', 'number_of_records'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }
}
