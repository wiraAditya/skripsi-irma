<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; 


class Kategori extends Model
{
    protected $table = 'kategori';
    
    protected $fillable = [
        'nama',
        'is_active',
    ];
    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class, 'menu_category_id');
    }

}