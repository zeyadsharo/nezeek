<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PropertyValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'property_id',
        'item_id',
        'item_type',
        'value',
        'json_value',
        'numeric_value',
        'boolean_value',
        'date_value',
        'datetime_value',
        'unit',
        'notes',
        'source',
        'is_verified'
    ];

    protected $casts = [
        'json_value' => 'array',
        'boolean_value' => 'boolean',
        'date_value' => 'date',
        'datetime_value' => 'datetime',
        'is_verified' => 'boolean',
        'numeric_value' => 'decimal:6',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function item()
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeVerified(Builder $query): void
    {
        $query->where('is_verified', true);
    }

    public function scopeByCategory(Builder $query, $categoryId): void
    {
        $query->where('category_id', $categoryId);
    }

    public function scopeByProperty(Builder $query, $propertyId): void
    {
        $query->where('property_id', $propertyId);
    }

    public function scopeByItem(Builder $query, $itemId, $itemType): void
    {
        $query->where('item_id', $itemId)->where('item_type', $itemType);
    }

    // Accessors
    public function getFormattedValueAttribute()
    {
        if ($this->json_value !== null) {
            return $this->json_value;
        }

        if ($this->numeric_value !== null) {
            $value = $this->numeric_value;
            if ($this->unit) {
                $value .= ' ' . $this->unit;
            }
            return $value;
        }

        if ($this->boolean_value !== null) {
            return $this->boolean_value ? 'Yes' : 'No';
        }

        if ($this->date_value !== null) {
            return $this->date_value->format('Y-m-d');
        }

        if ($this->datetime_value !== null) {
            return $this->datetime_value->format('Y-m-d H:i:s');
        }

        return $this->value;
    }

    public function getDisplayValueAttribute()
    {
        $value = $this->formatted_value;

        // If it's a select/multiselect property, show the option labels
        if ($this->property && $this->property->isSelectType()) {
            if (is_array($value)) {
                $options = $this->property->options ?? [];
                return collect($value)->map(function ($val) use ($options) {
                    return $options[$val] ?? $val;
                })->implode(', ');
            } else {
                $options = $this->property->options ?? [];
                return $options[$value] ?? $value;
            }
        }

        return $value;
    }

    // Methods
    public function setValue($value): void
    {
        $this->value = null;
        $this->json_value = null;
        $this->numeric_value = null;
        $this->boolean_value = null;
        $this->date_value = null;
        $this->datetime_value = null;

        if ($this->property) {
            switch ($this->property->type) {
                case 'number':
                case 'decimal':
                case 'range':
                    $this->numeric_value = $value;
                    break;
                case 'checkbox':
                    $this->boolean_value = (bool) $value;
                    break;
                case 'date':
                    $this->date_value = $value;
                    break;
                case 'datetime':
                    $this->datetime_value = $value;
                    break;
                case 'multiselect':
                    $this->json_value = is_array($value) ? $value : [$value];
                    break;
                case 'select':
                case 'radio':
                    $this->value = $value;
                    break;
                default:
                    $this->value = $value;
                    break;
            }
        } else {
            $this->value = $value;
        }
    }

    public function getValue()
    {
        if ($this->json_value !== null) {
            return $this->json_value;
        }
        if ($this->numeric_value !== null) {
            return $this->numeric_value;
        }
        if ($this->boolean_value !== null) {
            return $this->boolean_value;
        }
        if ($this->date_value !== null) {
            return $this->date_value;
        }
        if ($this->datetime_value !== null) {
            return $this->datetime_value;
        }
        return $this->value;
    }
}
