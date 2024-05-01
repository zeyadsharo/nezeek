<?php

namespace App\Models;

use GalleryJsonMedia\JsonMedia\Concerns\InteractWithMedia;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use InteractWithMedia;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'details', 'images', 'customer_id', 'department_id'
    ];

    protected $casts = [
        'details' => 'array',
        'images' => 'array'
    ];

        // for auto-delete media thumbnails

    protected function getFieldsToDeleteMedia(): array
    {
        return ['images'];
    }
    /**
     * Get all of the item's images.
    //  */
    // public function images()
    // {
    //     return $this->morphMany(Image::class, 'imageable');
    // }
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->customer_id = 1;
        });
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
