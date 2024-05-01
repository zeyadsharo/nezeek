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
        'title', 'details', 'imageable_id', 'imageable_type'
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
}
