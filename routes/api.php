<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\controllers\RiderController;
use App\Http\controllers\api\ApiAuthController;
use App\Http\Controllers\api\RazorpayController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//https://docs.google.com/document/d/1n2x3xLQtgPzL6gT9rckpuFkKt-N5i-Eobko-ulUB0fI/edit
// Route::any('rider/add',function(){
//     echo "helkjosaisdihdhffouhkjbjb";
//     die('stop');
// });

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard'); // Create a dashboard view
    })->name('dashboard');
});


Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('/login', [ApiAuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [ApiAuthController::class, 'logout']);


// Route::any('check',function(){
Route::any('rider/add',[RiderController::class,'add_rider']);
Route::any('rider/edit/{id}',[RiderController::class,'add_rider']);
Route::any('rider',[RiderController::class,'view_rider']);
Route::any('get_rider/{id}',[RiderController::class,'get_rider']);
Route::delete('rider/delete/{id}',[RiderController::class,'delete_rider']);

Route::post('/payment/create-order', [RazorpayController::class, 'createOrder']);
Route::post('/payment/verify-payment', [RazorpayController::class, 'verifyPayment']);
Route::post('/payment/fetch-order', [RazorpayController::class, 'paymentStatus']);
Route::post('/payment/fetch-all-payment', [RazorpayController::class, 'fetchAllPayments']);


