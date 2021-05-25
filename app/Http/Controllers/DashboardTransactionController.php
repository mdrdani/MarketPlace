<?php

namespace App\Http\Controllers;

use App\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardTransactionController extends Controller
{
    //
    public function index()
    {
        $selltransactions = TransactionDetail::with(['transaction.user', 'product.galleries'])
                                        ->whereHas('product', function($product){
                                            $product->where('users_id', Auth::user()->id);
                                        });
        
        $buytransactions = TransactionDetail::with(['transaction.user', 'product.galleries'])
                                        ->whereHas('transaction', function($transaction){
                                            $transaction->where('users_id', Auth::user()->id);
                                        });

        return view('pages.dashboard-transactions', [
            'selltransactions' => $selltransactions,
            'buytransactions' => $buytransactions
        ]);
    }

    public function details(){
        return view('pages.dashboard-transactions-details');
    }
}