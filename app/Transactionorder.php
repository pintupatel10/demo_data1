<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transactionorder extends Model
{
    protected $table="transaction_orders";
    protected $fillable = [
        'order_no','bank_reference','reference_no','purchase_date','total_amount','order_status','products'
    ];

    public function Transactionproduct()
    {
        return $this->hasMany('App\Transactionproduct', 'orderid');
    }

    public function Transactioncustomer()
    {
        return $this->hasMany('App\Transactioncustomer', 'orderid');
    }

    public function Transactionprice()
    {
        return $this->hasMany('App\Transactionprice', 'orderid');
    }

    public function Transactionchangehistory()
    {
        return $this->hasMany('App\Transactionchangehistory', 'orderid');
    }

    const POST_All = 'All';
    const POST_Public = 'Public';
    const POST_Private = 'Private';

    public static $post = [
        self::POST_All => 'All',
        self::POST_Public => 'Public',
        self::POST_Private => 'Private',
    ];

    const ORDER_STATUS_PENDING = "Pending";
}
