<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'link',
        'order',
        'status',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
