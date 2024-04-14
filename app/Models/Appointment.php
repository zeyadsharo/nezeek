<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
     protected $fillable = ['private_label', 'public_label', 'start_date', 'end_date', 'auto_delete_at', 'color', 'customer_id', 'is_private'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // add observer to set customer_id on create 
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->customer_id = auth()->user()->id;
        });
    }
    
}
