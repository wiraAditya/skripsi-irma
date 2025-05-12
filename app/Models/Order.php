<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'meja_id',
        'tanggal',
        'subtotal',
        'tax',
        'payment_method',
        'transaction_code',
        'catatan',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal' => 'datetime',
        'subtotal' => 'integer',
        'tax' => 'integer',
    ];

    /**
     * Payment method constants
     */
    const PAYMENT_CASH = 'method_cash';
    const PAYMENT_DIGITAL = 'method_digital';

    /**
     * Status constants
     */
    const STATUS_WAITING_CASH = 'status_waiting_cash';
    const STATUS_PAID = 'status_paid';
    const STATUS_PROCESS = 'status_process';
    const STATUS_DONE = 'status_done';
    const STATUS_CANCELED = 'status_canceled';

    /**
     * Get the table associated with this order.
     */
    public function meja(): BelongsTo
    {
        return $this->belongsTo(Meja::class, 'meja_id');
    }

    /**
     * Get the order details for this order.
     */
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetails::class);
    }
    
    public function recalculateTotal()
    {
        $subtotal = $this->orderDetails->sum(function($detail) {
            return $detail->harga * $detail->qty;
        });
        
        $this->update([
            'subtotal' => $subtotal,
            // If you have fixed tax rate (e.g., 10%)
            'tax' => $subtotal * 0.1,
        ]);
    }
}
