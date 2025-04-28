<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = 'order_details';

    protected $fillable = [
        'order_id',
        'menu_id',
        'quantity',
        'price',
        'total_price',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
