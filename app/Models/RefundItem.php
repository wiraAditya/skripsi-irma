<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'refund_id',
        'order_detail_id',
        'quantity',
        'refund_amount',
        'reason',
    ];

    protected $casts = [
        'refund_amount' => 'integer',
        'quantity' => 'integer',
    ];

    // Use proper Eloquent relationships
    public function refund()
    {
        return $this->belongsTo(Refund::class);
    }

    public function orderDetail()
    {
        return $this->belongsTo(OrderDetails::class, 'order_detail_id');
    }
}