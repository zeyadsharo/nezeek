<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'sector_id',
        'arabic_title',
        'kurdish_title',
        'display_order',
        'parent_id',
        'icon'
    ];

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
    public function properties()
    {
        return $this->belongsToMany(Property::class, 'category_properties')
        ->withPivot('display_order')
        ->orderByPivot('display_order');
    }
}
