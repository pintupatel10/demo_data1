<?php

namespace App;

use App\Helpers\ImageHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransportationList extends Model
{
    use SoftDeletes;
    protected $table="transportation_lists";
    protected $fillable = [
        'name','title','title_color','image','transportation_type','transportation_code','display','post','link','language','description','displayorder','status','payment_status'
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    public static $status = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'In Active',
    ];

    const LANGUAGE_ENG = 'English';
    const LANGUAGE_繁中 = '繁中';
    const LANGUAGE_簡= '簡';

    public static $language = [
        self::LANGUAGE_ENG => 'English',
        self::LANGUAGE_繁中 => '繁中',
        self::LANGUAGE_簡 => '簡',
    ];

    const Payment_STATUS_PAID = 'Paid';
    const Payment_STATUS_NOTPAID = 'NotPaid';

    public static $payment_status = [
        self::Payment_STATUS_PAID => 'Paid',
        self::Payment_STATUS_NOTPAID => 'Not Paid',
    ];

    const POST_Public = 'Public';
    const POST_Private = 'Private';

    public static $post = [
        self::POST_Public => 'Public',
    ];

    const DISPLAY_FullScreen = 'Full Screen';
    const DISPLAY_Simplified = 'Simplified';

    public static $display = [
        self::DISPLAY_FullScreen => 'Full Screen',
        self::DISPLAY_Simplified => 'Simplified',
    ];

    const TYPE_Normal = 'Normal';
    const TYPE_Turbojet = 'Turbojet';
    const TYPE_Contact_form= 'Contact form';

    public static $type = [
        self::TYPE_Normal => 'Normal',
        self::TYPE_Turbojet => 'Turbojet',
        self::TYPE_Contact_form => 'Contact form',
    ];

    public function TransportationCheckpoint()
    {
        return $this->hasMany('App\TransportationCheckpoint', 'detailid');
    }

    public function Transportationhighlight()
    {
        return $this->hasMany('App\Transportationhighlight', 'detailid');
    }

    public function TransportationPricegroup()
    {
        return $this->hasMany('App\TransportationPricegroup', 'detailid');
    }

    public function TransportationPrice()
    {
        return $this->hasMany('App\TransportationPrice', 'detailid');
    }

    public function TransportationTimetable()
    {
        return $this->hasMany('App\TransportationTimetable', 'detailid');
    }

    public function getImageThumbAttribute()
    {
        return ImageHelper::getThumbByOriginal($this->image);
    }

    public function isTurbojetType()
    {
        return in_array(self::TYPE_Turbojet, explode(',', $this->transportation_type));
    }


}
