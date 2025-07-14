<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_COMPLETED = 'completed';

    const METHOD_CASH = 'cash';
    const METHOD_TRANSFER = 'transfer';

    protected $fillable = [
        'order_id',
        'user_id',
        'refund_amount',
        'reason',
        'status',
        'refund_method',
        'proof_file', 
    ];
    

    protected $attributes = [
        'status' => self::STATUS_PENDING,
    ];

    protected $casts = [
        'refund_amount' => 'integer',
    ];

    // Relationships
    public function items()
    {
        return $this->hasMany(RefundItem::class, 'refund_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Helper methods
    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }
}