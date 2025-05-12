<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use Midtrans\Notification;

class MidtransService
{
    public function __construct()
    {
        // Configure Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Create Snap Payment Page
     *
     * @param array $params Payment parameters
     * @return object Snap token response
     */
    public function createTransaction(array $params)
    {
        
        return Snap::createTransaction($params);
    }

    /**
     * Process the Midtrans notification callback
     *
     * @return Notification
     */
    public function handleNotification()
    {
        return new Notification();
    }

    /**
     * Get transaction status
     *
     * @param string $id Order ID or transaction ID
     * @return array Transaction status response
     */
    public function getStatus($id)
    {
        return Transaction::status($id);
    }
}