<?php

namespace App\Services;

use Razorpay\Api\Api;
use Illuminate\Support\Facades\Log;

class RazorpayService
{
    protected $api;

    public function __construct()
    {
        $this->api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
    }

    // Create Razorpay order
    public function createOrder($amount)
    {
        $orderData = [
            'receipt'         => 'rcptid_11',
            'amount'          => $amount * 100, // amount in paise
            'currency'        => 'INR'
        ];
        $order = $this->api->order->create($orderData);
        return $order;
    }
    public function createOrderArr($array)
    {
        $order = $this->api->order->create($array);
        return $order;
    }

    // Fetch payment details
    public function fetchPayment($paymentId)
    {
        // return $this->api->payment->fetch($paymentId);
        return $this->api->order->fetch($paymentId)->payments();
    }
    public function checkPaymentStatus($orderId)
    {
        try {
            $order = $this->api->order->fetch($orderId);

            if ($order) {
                $payments = $order->payments();
                return response()->json($payments);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Capture payment
    public function capturePayment($paymentId, $amount)
    {
        return $this->api->payment->fetch($paymentId)->capture(['amount' => $amount * 100]);
    }

    // Verify payment signature
    public function verifySignature($attributes)
    {
        try {
            $this->api->utility->verifyPaymentSignature($attributes);
            return true;
        } catch (\Exception $e) {
            Log::error("Razorpay Signature Verification Failed: " . $e->getMessage());
            return false;
        }
    }
    public function getAllOrders($count = 10, $skip = 0)
    {
        try {
            $orders = $this->api->order->all([
                'count' => $count, // Number of orders to fetch
                'skip'  => $skip  // For pagination
            ]);

            return $orders;
        } catch (\Exception $e) {
            Log::error('Failed to fetch orders: ' . $e->getMessage());
            return null;
        }
    }
}
