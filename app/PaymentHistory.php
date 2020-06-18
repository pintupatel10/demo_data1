<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    const COLUMN_TYPE = 'type';
    const COLUMN_IS_SUCCESS = 'is_success';
    const COLUMN_IS_VALID = 'is_valid';
    const COLUMN_RESPONSE_DATA = 'response_data';           // JSON Encoded
    const COLUMN_CART_DATA = 'cart_data';                   // JSON Encoded
    const COLUMN_IS_AMOUNT_MATCH = 'is_amount_match';
    const COLUMN_REQUESTED_AMOUNT = 'requested_amount';
    const COLUMN_SETTLED_AMOUNT = 'settled_amount';
    const COLUMN_TRANSACTION_ID = 'transaction_id';
    const COLUMN_ACCOUNT_NO = 'account_no';
    const COLUMN_PAYMENT_METHOD = 'payment_method';
    const COLUMN_AUTHORIZATION_CODE = 'authorization_code';
}
