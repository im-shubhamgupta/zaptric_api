<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\controllers\api\UserController;
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


Route::middleware('throttle:strict-limit')->group(function () {
    Route::POST('user/add-address', [UserController::class, 'add_address']);
    Route::PUT('user/edit-address/{id}', [UserController::class, 'add_address']);
    Route::get('user-address/{id}', [UserController::class, 'get_user_address']);
    Route::delete('user/delete/{id}', [UserController::class, 'delete_shipping_address']);
});


Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('/login', [ApiAuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [ApiAuthController::class, 'logout']);
/*
Route::POST('user/add-address',[UserController::class,'add_address']);
Route::PUT('user/edit-address/{id}',[UserController::class,'add_address']);
Route::get('user-address/{id}',[UserController::class,'get_user_address']);
Route::delete('user/delete/{id}',[UserController::class,'delete_shipping_address']);
*/
Route::post('/payment/create-order', [RazorpayController::class, 'createOrder']);
// Route::post('/payment/verify-payment', [RazorpayController::class, 'verifyPayment']);
Route::post('/payment/order-status', [RazorpayController::class, 'paymentStatus']);
Route::post('/payment/fetch-all-payment', [RazorpayController::class, 'fetchAllPayments']);


