<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Transaction;
use App\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Exception;

use Midtrans\Snap;
use Midtrans\Config;

class CheckoutController extends Controller
{
    //
    public function process(Request $request)
    {
        // save user data
        $user = Auth::user();
        $user->update($request->except('total_price'));

        // process checkout
        $code = 'STORE-' . mt_rand(000000,999999);
        $carts = Cart::with(['product','user'])
                                        ->where('users_id', Auth::user()->id)
                                        ->get();
        
        // transaction create
        $transactions = Transaction::create([
            'users_id' => Auth::user()->id,
            'insurance_price' => 0,
            'shipping_price' => 0,
            'total_price' => $request->total_price,
            'transaction_status' => 'PENDING',
            'code' => $code,
        ]);

        foreach($carts as $cart) 
        {
            $trx =  'TRX-' . mt_rand(000000,999999);

            TransactionDetail::create([
            'transactions_id' => $transactions->id,
            'products_id' => $cart->product->id,
            'price' => $cart->product->price,
            'shipping_status' => 'PENDING',
            'resi' => '',
            'code' => $trx,
        ]);
        }

        // config midtrans
        // Set your Merchant Server Key
        Config::$serverKey = config('services.midtrans.serverKey');
            // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        Config::$isProduction = config('services.midtrans.isProduction');
            // Set sanitization on (default)
        Config::$isSanitized = config('services.midtrans.isSanitized');
            // Set 3DS transaction for credit card to true
        Config::$is3ds = config('services.midtrans.is3ds');

        // buat array untuk di kirim ke midtrans
        $midtrans = [
            'transaction_details' => [
                'order_id' => $code,
                'gross_amount' => (int) $request->total_price
            ],
            'customer_detai;s' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
            'enabled_payments' => [
                'gopay', 'bank_transfer'
            ],
            'vtweb' => []
            ];

            try {
            // Get Snap Payment Page URL
            $paymentUrl = Snap::createTransaction($midtrans)->redirect_url;
            
            // Redirect to Snap Payment Page
            return redirect($paymentUrl);
            }
            catch (Exception $e) {
            echo $e->getMessage();
            }
    }

    public function callback(Request $request)
    {

    }
}
