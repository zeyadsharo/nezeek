<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title',
        'description',
        'type',
        'options',
        'default_value',
        'unit',
        'placeholder',
        'is_required',
        'validation_rules',
        'min_length',
        'max_length',
        'min_value',
        'max_value',
        'min_selections',
        'max_selections',
        'icon',
        'color',
        'display_order',
        'is_searchable',
        'is_filterable',
        'is_sortable',
        'is_unique',
        'is_encrypted',
        'group',
        'conditional_logic',
        'help_text'
    ];

    protected $casts = [
        'options' => 'array',
        'default_value' => 'array',
        'conditional_logic' => 'array',
        'is_required' => 'boolean',
        'is_searchable' => 'boolean',
        'is_filterable' => 'boolean',
        'is_sortable' => 'boolean',
        'is_unique' => 'boolean',
        'is_encrypted' => 'boolean',
        'min_value' => 'decimal:6',
        'max_value' => 'decimal:6',
    ];

    // Relationships
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_properties')
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
            ]);
    }

    public function propertyGroups()
    {
        return $this->belongsToMany(PropertyGroup::class, 'property_group_properties')
            ->withPivot([
                'custom_label',
                'custom_help_text',
                'display_order',
                'is_visible',
                'is_editable',
                'is_required'
            ]);
    }

    public function propertyGroup()
    {
        return $this->belongsTo(PropertyGroup::class, 'group', 'name');
    }

    public function propertyValues()
    {
        return $this->hasMany(PropertyValue::class);
    }

    // Scopes
    public function scopeSearchable(Builder $query): void
    {
        $query->where('is_searchable', true);
    }

    public function scopeFilterable(Builder $query): void
    {
        $query->where('is_filterable', true);
    }

    public function scopeSortable(Builder $query): void
    {
        $query->where('is_sortable', true);
    }

    public function scopeByType(Builder $query, string $type): void
    {
        $query->where('type', $type);
    }

    public function scopeByGroup(Builder $query, string $group): void
    {
        $query->where('group', $group);
    }

    public function scopeRequired(Builder $query): void
    {
        $query->where('is_required', true);
    }

    // Accessors
    public function getIconUrlAttribute()
    {
        return $this->icon ? Storage::disk('public')->url($this->icon) : null;
    }

    public function getFullTitleAttribute(): string
    {
        return $this->title;
    }

    public function getFullDescriptionAttribute(): ?string
    {
        return $this->description;
    }

    public function getFullPlaceholderAttribute(): ?string
    {
        return $this->placeholder;
    }

    public function getFullHelpTextAttribute(): ?string
    {
        return $this->help_text;
    }

    // Methods
    public function isSelectType(): bool
    {
        return in_array($this->type, ['select', 'multiselect', 'radio']);
    }

    public function isNumericType(): bool
    {
        return in_array($this->type, ['number', 'decimal', 'range']);
    }

    public function isDateType(): bool
    {
        return in_array($this->type, ['date', 'datetime', 'time']);
    }

    public function isFileType(): bool
    {
        return in_array($this->type, ['file', 'image']);
    }

    public function getValidationRules(): array
    {
        $rules = [];

        if ($this->is_required) {
            $rules[] = 'required';
        } else {
            $rules[] = 'nullable';
        }

        // Type-specific rules
        switch ($this->type) {
            case 'email':
                $rules[] = 'email';
                break;
            case 'url':
                $rules[] = 'url';
                break;
            case 'number':
            case 'decimal':
                $rules[] = 'numeric';
                if ($this->min_value !== null) {
                    $rules[] = "min:{$this->min_value}";
                }
                if ($this->max_value !== null) {
                    $rules[] = "max:{$this->max_value}";
                }
                break;
            case 'text':
            case 'textarea':
                if ($this->min_length !== null) {
                    $rules[] = "min:{$this->min_length}";
                }
                if ($this->max_length !== null) {
                    $rules[] = "max:{$this->max_length}";
                }
                break;
            case 'select':
            case 'radio':
                if ($this->options) {
                    $rules[] = 'in:' . implode(',', $this->options);
                }
                break;
            case 'multiselect':
                if ($this->options) {
                    $rules[] = 'array';
                    $rules[] = 'in:' . implode(',', $this->options);
                    if ($this->min_selections !== null) {
                        $rules[] = "min:{$this->min_selections}";
                    }
                    if ($this->max_selections !== null) {
                        $rules[] = "max:{$this->max_selections}";
                    }
                }
                break;
        }

        // Custom validation rules
        if ($this->validation_rules) {
            $customRules = explode('|', $this->validation_rules);
            $rules = array_merge($rules, $customRules);
        }

        return $rules;
    }

    public function getOptionsForCategory(Category $category): array
    {
        $pivot = $this->categories()->where('category_id', $category->id)->first()?->pivot;

        if ($pivot && $pivot->custom_options) {
            return $pivot->custom_options;
        }

        return $this->options ?? [];
    }
}
