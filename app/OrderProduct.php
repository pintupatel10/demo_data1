<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class OrderProduct extends Model
{
    use Auditable;

    const COLUMN_ID = 'id';
    const COLUMN_ORDER_ID = 'order_id';
    const COLUMN_STATUS = 'status';
    const COLUMN_SUB_TOTAL = 'sub_total';
    const COLUMN_DISCOUNT = 'discount';
    const COLUMN_UNIT_SERVICE_CHARGE = 'unit_service_charge';
    const COLUMN_TOTAL_SERVICE_CHARGE = 'total_service_charge';
    const COLUMN_TOTAL_AMOUNT = 'total_amount';
    const COLUMN_PACKAGE_QUANTITY = 'package_quantity';
    const COLUMN_TYPE = 'type';
    const COLUMN_PRODUCT_ID = 'product_id';
    const COLUMN_PRODUCT_PRICE_GROUP_ID = 'product_price_group_id';
    const COLUMN_PRODUCT_TITLE = 'product_title';
    const COLUMN_PRODUCT_DESCRIPTION = 'product_description';
    const COLUMN_PRODUCT_PRICE_GROUP_TITLE = 'product_price_group_title';
    const COLUMN_DATE = 'date';
    const COLUMN_TIME = 'time';
    const COLUMN_CLASS = 'class';
    const COLUMN_HOTEL = 'hotel';
    const COLUMN_TITLE = 'title';
    const COLUMN_FIRST_NAME = 'first_name';
    const COLUMN_LAST_NAME = 'last_name';
    const COLUMN_NATIONALITY_ID = 'nationality_id';
    const COLUMN_EMAIL = 'email';
    const COLUMN_COUNTRY_CODE = 'country_code';
    const COLUMN_TEL = 'tel';
    const COLUMN_PROMOCODE = 'promocode';
    const COLUMN_REMARK = 'remark';
    const COLUMN_LANGUAGE = 'language';
    const COLUMN_IS_PRIVATE = 'is_private';
    const COLUMN_VOUCHER_NO = 'voucher_no';
    const COLUMN_E_TICKET_NO = 'e_ticket_no';
    const COLUMN_LIVE_TICKET_NO = 'live_ticket_no';
    const COLUMN_REFERENCE_NO = 'reference_no';
    const COLUMN_CONFIRMATION = 'confirmation';
    const COLUMN_CONFIRMATION_SENT_AT = 'confirmation_sent_at';
    const COLUMN_CONFIRMED_BY = 'confirmed_by';
    const COLUMN_COMPLETED_AT = 'completed_at';
    const COLUMN_VIRTUAL_REPORT_GENERATED_AT = 'virtual_report_generated_at';

    const STATUS_IN_QUEUE = "in-queue";
    const STATUS_PENDING = "pending";
    const STATUS_CONFIRM_DRAFTED = "confirm_drafted";
    const STATUS_CONFIRMED = "confirmed";
    const STATUS_CANCELED = "canceled";
    const STATUS_COMPLETED = "completed";

    const TYPE_TOUR = "tour";
    const TYPE_TICKET = "ticket";
    const TYPE_TRANSPORTATION = "transportation";
    const TYPE_COMBO = "combo";

    // For search only
    const TYPE_PRIVATE_TOUR = "private-tour";
    const TYPE_PRIVATE_TRANSPORTATION = "private-transportation";
    //

    public $incrementing = false;

    protected $dates = [
        self::COLUMN_DATE,
        self::COLUMN_CONFIRMATION_SENT_AT,
        self::COLUMN_COMPLETED_AT,
        self::COLUMN_VIRTUAL_REPORT_GENERATED_AT,
    ];

    public static $valid_status = [
        OrderProduct::STATUS_IN_QUEUE,
        OrderProduct::STATUS_PENDING,
        OrderProduct::STATUS_CONFIRM_DRAFTED,
        OrderProduct::STATUS_CONFIRMED,
        OrderProduct::STATUS_COMPLETED,
    ];

    public static $types = [
        self::TYPE_TOUR => "Tour",
        self::TYPE_TICKET => "Ticket",
        self::TYPE_TRANSPORTATION => "Transportation",
        self::TYPE_COMBO => "Combo",
    ];

    public static $search_types = [
        self::TYPE_TOUR => "Tour",
        self::TYPE_TICKET => "Ticket",
        self::TYPE_TRANSPORTATION => "Transportation",
        self::TYPE_COMBO => "Combo",
        self::TYPE_PRIVATE_TOUR => "Private Tour",
        self::TYPE_PRIVATE_TRANSPORTATION => "Private Transportation",
    ];

    public static $search_status = [
        OrderProduct::STATUS_IN_QUEUE => 'In-Queue',
        OrderProduct::STATUS_PENDING => 'Pending',
        OrderProduct::STATUS_CONFIRM_DRAFTED => 'Confirm Drafted',
        OrderProduct::STATUS_CONFIRMED => 'Confirmed',
        OrderProduct::STATUS_COMPLETED => 'Completed',
        OrderProduct::STATUS_CANCELED => 'Cancelled',
    ];

    protected $fillable = [
        self::COLUMN_VOUCHER_NO, self::COLUMN_REFERENCE_NO, self::COLUMN_E_TICKET_NO, self::COLUMN_LIVE_TICKET_NO,
    ];

    protected $auditableEvents = [
        'updated',
    ];

    public function order()
    {
        return $this->belongsTo('App\Order');
    }

    public function packages()
    {
        return $this->hasMany('App\OrderProductPackage');
    }

    public function nationality()
    {
        return $this->belongsTo('App\Nationality');
    }

    public function disneyland_reserves()
    {
        return $this->hasMany('App\DisneylandReserve');
    }

    public function oceanpark_reserves()
    {
        return $this->hasMany('App\OceanParkReserve');
    }

    public function attachments()
    {
        return $this->hasMany('App\OrderProductAttachment');
    }

    public function confirmed_user()
    {
        return $this->belongsTo('App\User', self::COLUMN_CONFIRMED_BY)->withTrashed();
    }

    public function turbojets()
    {
        return $this->hasMany('App\OrderProductTurbojet');
    }

    public function turbojet_reserve()
    {
        return $this->HasOne('App\TurbojetReserve');
    }

    public function getNameAttribute()
    {
        if ($this->{self::COLUMN_LANGUAGE} == "en")
            return $this->{self::COLUMN_FIRST_NAME} . ' ' . $this->{self::COLUMN_LAST_NAME};
        else
            return $this->{self::COLUMN_LAST_NAME} . $this->{self::COLUMN_FIRST_NAME};
    }

    public function getPhoneAttribute()
    {
        if ($this->{self::COLUMN_TEL})
            return $this->{self::COLUMN_COUNTRY_CODE} . ' ' . $this->{self::COLUMN_TEL};
        else
            return "";
    }

    public function transformAudit(array $data)
    {
        if (Arr::has($data, 'new_values.date')) {
            Arr::set($data, 'new_values.date',  $this->{self::COLUMN_DATE}->format('Y-m-d'));
        }

        return $data;
    }

    public function getReportTypeCount($type)
    {
        $sum = 0;
        foreach ($this->packages as $package)
        {
            if ($package->price->report_price_type == $type)
                $sum += $package->{OrderProductPackage::COLUMN_QUANTITY};
        }
        return $sum;
    }

    public function getReportTypePrice($type)
    {
        $sum = 0;
        foreach ($this->packages as $package)
        {
            if ($package->price->report_price_type == $type)
                $sum += $package->{OrderProductPackage::COLUMN_UNIT_ORIGINAL_PRICE};
        }
        return $sum;
    }
}
