<?php

namespace App\Http\Controllers;

use App\Transaction;
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
                                        })->orderByDesc('created_at')->get();
        
        $buytransactions = TransactionDetail::with(['transaction.user', 'product.galleries'])
                                        ->whereHas('transaction', function($transaction){
                                            $transaction->where('users_id', Auth::user()->id);
                                        })->orderByDesc('created_at')->get();

        return view('pages.dashboard-transactions', [
            'selltransactions' => $selltransactions,
            'buytransactions' => $buytransactions
        ]);
    }

    public function details(Request $request, $id)
    {
        $transactions = TransactionDetail::with(['transaction.user', 'product.galleries'])
                                                                    ->findOrFail($id);

        return view('pages.dashboard-transactions-details', [
            'transaction' => $transactions
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $item = TransactionDetail::findOrFail($id);
        $item->update($data);

        return redirect()->route('dashboard-transaction-details', $id);
    }
}