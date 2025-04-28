<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes;

    protected $table = 'menus';

    protected $casts = [
        'is_active' => 'boolean'
    ];

    protected $fillable = [
        'menu_category_id',
        'name',
        'description',
        'price',
        'image',
        'is_active'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function menuCategory()
    {
        return $this->belongsTo(MenuCategory::class);
    }
}
