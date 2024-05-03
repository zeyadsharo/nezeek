<?php

namespace App\Models;

use GalleryJsonMedia\JsonMedia\Concerns\InteractWithMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Item extends Model
{
    use InteractWithMedia;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'details', 'images','department_id'
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

    public static function boot()
    {
        parent::boot();

        // static::creating(function ($model) {
        //     $model->customer_id = 1;
        // });
        // static::addGlobalScope('customer', function (Builder $builder) {
        //     $builder->where('customer_id', auth()->user()->customer_id);
        // });
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
