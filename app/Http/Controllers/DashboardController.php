<?php

namespace App\Http\Controllers;

use App\TransactionDetail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
     //
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $transactions = TransactionDetail::with(['transaction.user', 'product.galleries'])
                                        ->whereHas('product', function($product){
                                            $product->where('users_id', Auth::user()->id);
                                        })->orderByDesc('created_at')->limit(5);

        $revenue = $transactions->get()->reduce(function($carry, $item) {
            return $carry = $item->price;
        });

        $customer = User::count();
        return view('pages.dashboard',[
            'transaction_count' => $transactions->count(),
            'transaction_data' => $transactions->get(),
            'revenue' => $revenue,
            'customer' => $customer
        ]);
    }


}