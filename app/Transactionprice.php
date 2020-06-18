<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transactionprice extends Model
{
    protected $table="price_products";
    protected $fillable = [
        'customerid','orderid','productid','title','type','type_id','qty','price','total','price_type','price_id',
    ];

    public function Transactionorder()
    {
        return $this->belongsTo('App\Transactionorder', 'id');
    }

    public function Transactioncustomer()
    {
        return $this->belongsTo('App\Transactioncustomer', 'id');
    }

    public function Transactionproduct()
    {
        return $this->belongsTo('App\Transactionproduct', 'id');
    }
}
