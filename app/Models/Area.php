<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;
      protected $fillable = ['arabic_title', 'kurdish_title', 'parent_id', 'latitude', 'longitude'];

    public function parentArea()
    {
        return $this->belongsTo(Area::class, 'parent_id');
    }

    public function childAreas()
    {
        return $this->hasMany(Area::class, 'parent');
    }
}
