<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'table_id',
        'code',
        'date',
        'name',
        'email',
        'total_price',
        'payment_type',
        'payment_url',
        'payment_status',
    ];

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
