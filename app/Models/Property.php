<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'arabic_title',
        'kurdish_title',
        'is_required',
        'type',
        'values',
        'unit',
        'icon',
        'validation_rule'
    ];
}
