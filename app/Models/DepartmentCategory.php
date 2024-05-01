<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'display_order',
        'icon',
        'title_ar',
        'title_ku',
    ];

    /**
     * Get the departments for the department category.
     */
    public function departments()
    {
        return $this->hasMany(Department::class);
    }
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->customer_id = 1;
        });
    }
}