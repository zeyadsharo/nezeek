<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'details', 'imageable_id', 'imageable_type'
    ];

    /**
     * Get all of the item's images.
     */
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}