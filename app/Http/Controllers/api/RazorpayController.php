<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\RazorpayService;
use App\Models\Payment;

class RazorpayController extends Controller
{

    protected $razorpayService;
    protected $response = array('check'=> 'failed', 'message' => 'something Error');

    public function __construct(RazorpayService $razorpayService)
    {
        $this->razorpayService = $razorpayService;
    }

    // API to create Razorpay order
    public function createOrder(Request $request)
    {
        $amount = $request->input('amount');
        $receipt = $request->input('receipt');
        $user_id = $request->input('user_id');

        if (!$amount) {
            $this->response['message'] = 'Amount is required';
            return response()->json($this->response, 400);
        }
        if (!$receipt) {
            $this->response['message'] = 'receipt is required';
            return response()->json($this->response, 400);
        }
        if (!$user_id) {
            $this->response['message'] = 'user_id is required';
            return response()->json($this->response, 400);
        }
        $data = array(
                'receipt' => $receipt,
                'amount' => $amount * 100,
                'currency' => 'INR',
                );
        // echo json_encode($data);
        $order = $this->razorpayService->createOrderArr($data );
        $pay = new payment;

        if(count((array)$order) > 0){
            $pay->user_id = $user_id;
            $pay->order_id = $order['id'];
            $pay->order_amount = $order['amount'] / 100;
            $pay->created_at = date('Y-m-d H:i:s');
            $pay->updated_at = date('Y-m-d H:i:s');
            $pay->save();

            $this->response['check'] = 'success';
            $this->response['message'] = 'order created succesfully';
            $this->response['data'] = [
                'order_id' => $order['id'],
                'amount'   => $order['amount'] / 100,
                'currency' => $order['currency']
            ];
            // $this->response['all_data'] = (array)$order; //log
        }
        return response()->json($this->response);
    }

   /* public function verifyPayment(Request $request)
    {
        $signature = $request->input('razorpay_signature');
        $paymentId = $request->input('razorpay_payment_id');
        $orderId = $request->input('razorpay_order_id');

        if (!$signature || !$paymentId || !$orderId) {
            $this->response['message'] = 'Invalid payment details';
            return response()->json($this->response, 400);
        }

        $attributes = [
            'razorpay_order_id' => $orderId,
            'razorpay_payment_id' => $paymentId,
            'razorpay_signature' => $signature,
        ];

        // Verify the payment signature
        if ($this->razorpayService->verifySignature($attributes)) {
            return response()->json(['success' => true, 'message' => 'Payment verified successfully!']);
        } else {
            return response()->json(['error' => 'Payment verification failed'], 400);
        }
    }*/
    /*public function fetchOrder(Request $request){
        $order_id =  $request->input('order_id');
        $orders = $this->razorpayService->fetchPayment($order_id);

        // print_r( $orders );
        if($orders){
            $this->response['check'] = 'success';
            $this->response['message'] = 'fetch order status';
            $this->response['data'] = (array)$orders;
        }
        return response()->json($this->response);
    }*/

    public function fetchAllPayments(Request $request)
    {
        $count = $request->input('count', 10);
        $skip = $request->input('skip', 0);

        $orders = $this->razorpayService->getAllOrders($count, $skip);

        if ($orders) {
            return response()->json($orders);
        } else {
            return response()->json(['error' => 'Failed to fetch orders'], 500);
        }
    }

    public function paymentStatus(Request $request){ //order-status
        $order_id =  $request->input('order_id');
        if (!$order_id) {
            $this->response['message'] = 'order_id is required';
            return response()->json($this->response, 400);
        }
        $orders = $this->razorpayService->checkPaymentStatus($order_id);

        // print_r( $orders );
        if($orders){
            $this->response['check'] = 'success';
            $this->response['message'] = 'fetch order status';
            $this->response['data'] = (array)$orders;
        }
        return response()->json($this->response);
    }
}
