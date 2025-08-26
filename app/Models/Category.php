<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sector_id',
        'title',
        'description',
        'parent_id',
        'display_order',
        'icon',
        'image',
        'color',
        'is_active',
        'is_featured',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'slug'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    // Relationships
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
            ->withPivot([
                'display_order',
                'is_visible',
                'is_editable',
                'is_required',
                'custom_label',
                'custom_help_text',
                'custom_validation_rules',
                'custom_options',
                'show_when',
                'hide_when'
            ])
            ->orderByPivot('display_order');
    }

    public function propertyValues()
    {
        return $this->hasMany(PropertyValue::class);
    }

    // Scopes
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query): void
    {
        $query->where('is_featured', true);
    }

    public function scopeBySector(Builder $query, $sectorId): void
    {
        $query->where('sector_id', $sectorId);
    }

    public function scopeRoot(Builder $query): void
    {
        $query->whereNull('parent_id');
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
    public function isRoot(): bool
    {
        return is_null($this->parent_id);
    }

    public function isChild(): bool
    {
        return !is_null($this->parent_id);
    }

    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }

    public function getAncestors()
    {
        $ancestors = collect();
        $current = $this->parent;

        while ($current) {
            $ancestors->push($current);
            $current = $current->parent;
        }

        return $ancestors->reverse();
    }

    public function getBreadcrumb(): array
    {
        $breadcrumb = $this->getAncestors()->toArray();
        $breadcrumb[] = $this->toArray();
        return $breadcrumb;
    }
}
