<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'menu_id',
        'qty',
        'harga',
        'catatan',
    ];
    protected $casts = [
        'qty' => 'integer',
        'harga' => 'integer',
    ];

    // Get total refunded quantity for this order detail
    public function getTotalRefundedQuantity()
    {
        return $this->refundItems()->sum('quantity');
    }

    // Get net quantity (original - refunded)
    public function getNetQuantity()
    {
        return $this->qty - $this->getTotalRefundedQuantity();
    }

    // Get total refunded amount for this order detail
    public function getTotalRefundedAmount()
    {
        return $this->refundItems()->sum('refund_amount');
    }

    // Get net amount (original - refunded)
    public function getNetAmount()
    {
        return ($this->qty * $this->harga) - $this->getTotalRefundedAmount();
    }

    // Check if this item can be refunded (has remaining quantity)
    public function canBeRefunded($requestedQty = 1)
    {
        return $this->getNetQuantity() >= $requestedQty;
    }

    // Get available quantity for refund
    public function getAvailableRefundQuantity()
    {
        return $this->getNetQuantity();
    }

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function refundItems()
    {
        return $this->hasMany(RefundItem::class, 'order_detail_id');
    }
}