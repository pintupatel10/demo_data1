<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transactioncustomer extends Model
{
    protected $table="transaction_customers";
    protected $fillable = [
       'orderid','name','phone','email',
    ];

    public function Transactionorder()
    {
        return $this->belongsTo('App\Transactionorder', 'id');
    }

    public function Transactionproduct()
    {
        return $this->hasMany('App\Transactionproduct', 'customerid');
    }

    public function Transactionprice()
    {
        return $this->hasMany('App\Transactionprice', 'customerid');
    }

    public function Transactionchangehistory()
    {
        return $this->hasMany('App\Transactionchangehistory', 'customerid');
    }
}
