<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Emailset extends Model
{
    use SoftDeletes;
    protected $table="email_setups";
    protected $fillable = [
        'type','displayorder','status'
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    public static $status = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'In Active',
    ];

    const Type_contact = 'contact us email address';
    const Type_Tour_within_48 = 'Tour email within 48 hours';
    const Type_Tour_outof_48 = 'Tour email out of 48 hours';
    const Type_Ticket_within_48 = 'Ticket email within 48 hours';
    const Type_Ticket_outof_48 = 'Ticket email out of 48 hours';
    const Type_Transportation_within_48 = 'Transportation within 48 hours';
    const Type_Transportation_outof_48 = 'Transportation out of 48 hours';
    const Type_Transportation_contact_form = 'Transportation contact form';
    const Type_Combo_within_48 = 'Combo email within 48 hours';
    const Type_Combo_outof_48 = 'Combo email out of 48 hours';
    const TYPE_DEFAULT_ORDER_NOTIFICATION = 'Default Order Notification';
    const TYPE_PAYMENT_FAILED = 'Payment Failed';


    public static $type = [
        self::Type_contact => 'contact us email address',
        self::Type_Tour_within_48 => 'Tour email within 48 hours',
        self::Type_Tour_outof_48 => 'Tour email out of 48 hours',
        self::Type_Ticket_within_48 => 'Ticket email within 48 hours',
        self::Type_Ticket_outof_48 => 'Ticket email out of 48 hours',
        self::Type_Transportation_within_48 => 'Transportation within 48 hours',
        self::Type_Transportation_outof_48 => 'Transportation out of 48 hours',
        self::Type_Combo_within_48 => 'Combo within 48 hours',
        self::Type_Combo_outof_48 => 'Combo out of 48 hours',
        self::Type_Transportation_contact_form => 'Transportation contact form',
        self::TYPE_DEFAULT_ORDER_NOTIFICATION => 'Default Order Notification',
        self::TYPE_PAYMENT_FAILED => "Payment Failed",
    ];

    public function MailAddress()
    {
        return $this->hasMany('App\MailAddress', 'emailid');
    }
}
