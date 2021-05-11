<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
 //
     protected $fillable = [
        'transactions_id',
        'products_id',
        'price',
        'resi',
        'code',
        'shipping_status'
    ];

    protected $hidden = [

    ]; 
}
