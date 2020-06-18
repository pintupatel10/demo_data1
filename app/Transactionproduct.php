<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transactionproduct extends Model
{
    protected $table="transaction_products";
    protected $fillable = [
        'orderid','product_name','reference_no','subproduct_no','voucher_no','e_ticket_no','live_ticket_no','post','date','time','hotel_stay','title','fname','lname','passport','email','pnumber','snumber','promocode','remark','adult','adult_price','children','children_price','total','status'
        ,'customerid','type','subtotal','discount'
    ];

    const Title_Mr = 'Mr';
    const Title_Ms = 'Ms';
    const Title_Mrs= 'Mrs';

    public static $title = [
        self::Title_Mr => 'Mr',
        self::Title_Ms => 'Ms',
        self::Title_Mrs => 'Mrs',
    ];


    const POST_Public = 'Public';
    const POST_Private = 'Private';

    public static $post = [
        self::POST_Public => 'Public',
        self::POST_Private => 'Private',
    ];

    const STATUS_Confirmed = 'Confirmed';
    const STATUS_Pending = 'Pending';
    const STATUS_Cancelled = 'Cancelled';
    const STATUS_Complete = 'Complete';

    public static $status = [
        self::STATUS_Confirmed => 'Confirmed',
        self::STATUS_Pending => 'Pending',
        self::STATUS_Cancelled => 'Cancelled',
        self::STATUS_Complete => 'Complete',
    ];

    public function Transactionorder()
    {
        return $this->belongsTo('App\Transactionorder','orderid');
    }

    public function Transactioncustomer()
    {
        return $this->belongsTo('App\Transactioncustomer', 'id');
    }

    public function Transactionprice()
    {
        return $this->hasMany('App\Transactionprice', 'productid');
    }

    public function Transactionchangehistory()
    {
        return $this->hasMany('App\Transactionchangehistory', 'productid');
    }
}
