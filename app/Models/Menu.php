<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';
    
    protected $fillable = [
        'menu_category_id',
        'nama',
        'deskripsi',
        'harga',
        'gambar',
        'is_active'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'menu_category_id');
    }
}