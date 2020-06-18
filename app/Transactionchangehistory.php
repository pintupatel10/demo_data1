<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transactionchangehistory extends Model
{
    protected $table="change_histroys";
    protected $fillable = [
        'customerid','orderid','productid','change_from','change_to','change_by','remark1'
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
