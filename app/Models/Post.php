<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'icon', 'post_date', 'display_order', 'content', 'customer_id', 'auto_delete_at'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
