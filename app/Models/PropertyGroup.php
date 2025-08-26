<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title',
        'description',
        'display_order',
        'icon',
        'color',
        'is_collapsible',
        'is_expanded_by_default',
        'is_active'
    ];

    protected $casts = [
        'is_collapsible' => 'boolean',
        'is_expanded_by_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function properties()
    {
        return $this->hasMany(Property::class, 'group', 'name');
    }

    // Scopes
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('display_order');
    }

    // Accessors
    public function getFullTitleAttribute(): string
    {
        return $this->title;
    }

    public function getFullDescriptionAttribute(): ?string
    {
        return $this->description;
    }

    // Methods
    public function getPropertiesForCategory(Category $category)
    {
        return $this->properties()
            ->whereHas('categories', function ($query) use ($category) {
                $query->where('category_id', $category->id);
            })
            ->orderBy('display_order')
            ->get();
    }
}
