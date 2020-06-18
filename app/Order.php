<?php

namespace App;

use App\Helpers\Toolkit;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_TOTAL_AMOUNT = 'total_amount';
    const COLUMN_STATUS = 'status';
    const COLUMN_PAYMENT_METHOD = 'payment_method';
    const COLUMN_PAYMENT_REFERENCE = 'payment_reference';
    const COLUMN_PAYMENT_HISTORY_ID = 'payment_history_id';
    const COLUMN_ACCOUNT_NO = 'account_no';
    const COLUMN_LANGUAGE = 'language';
    const COLUMN_REMARK = 'remark';
    const COLUMN_ENQUIRY_NO = 'enquiry_no';

    const STATUS_IN_QUEUE = "in-queue";
    const STATUS_PENDING = "pending";
    const STATUS_CONFIRMED = "confirmed";
    const STATUS_CANCELLED = "cancelled";

    const PAYMENT_METHOD_VISA = "visa";
    const PAYMENT_METHOD_MASTERCARD = "mastercard";
    const PAYMENT_METHOD_UNIONPAY = "unionpay";

    public $incrementing = false;

    public static $payment_methods = [
        self::PAYMENT_METHOD_VISA => 'VISA',
        self::PAYMENT_METHOD_MASTERCARD => 'MasterCard',
        self::PAYMENT_METHOD_UNIONPAY => 'UnionPay',
    ];

    public function products()
    {
        return $this->hasMany('App\OrderProduct');
    }

    public function payment_history()
    {
        return $this->belongsTo('App\PaymentHistory');
    }

    public function confirmations()
    {
        return $this->hasMany('App\OrderConfirmation');
    }

    public function messages()
    {
        return $this->hasMany('App\OrderMessage');
    }

    public function notification_users()
    {
        return $this->belongsToMany('App\User', 'order_notifications', 'order_id', 'user_id');
    }

    public static function newEnquireNo() {
        do {
            $code = Toolkit::mt_rand_str(32, Toolkit::STR_azAZ09);
        } while (Order::where(self::COLUMN_ENQUIRY_NO, $code)->count() != 0);

        return $code;
    }

    public function getDistinctCustomers()
    {
        return self::getDistinctCustomersForProducts($this->products);
    }

    public function getDistinctCustomersForProducts($order_products)
    {
        $fields = [
            OrderProduct::COLUMN_COUNTRY_CODE,
            OrderProduct::COLUMN_TEL,
            OrderProduct::COLUMN_FIRST_NAME,
            OrderProduct::COLUMN_LAST_NAME,
            OrderProduct::COLUMN_EMAIL,
        ];

        $customers = collect();
        foreach ($order_products as $product)
        {
            $contains = $customers->contains(function ($key, $value) use ($product, $fields) {
                foreach ($fields as $field)
                {
                    if (strcasecmp($value->{$field}, $product->{$field}) != 0)
                        return false;
                }

                return true;
            });

            if (!$contains)
            {
                $customers->push($product);
            }
        }

        return $customers->all();
    }

    public function getCompletedCount()
    {
        return $this->products()->whereIn(OrderProduct::COLUMN_STATUS, [
                OrderProduct::STATUS_CANCELED,
                OrderProduct::STATUS_COMPLETED,
            ])->count() . " / " . $this->products->count();
    }

    public function updateNotificationUser()
    {
        if (!\Auth::user())
            return;

        $user_id = \Auth::user()->id;

        // Ignore admin user
        if ($user_id == 1)
            return;

        if (!$this->notification_users->contains($user_id))
            $this->notification_users->attach($user_id);
    }
}
