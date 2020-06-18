<?php

namespace App;

use App\Helpers\ImageHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TourList extends Model
{
    use SoftDeletes;
    protected $table="tour_lists";
    protected $fillable = [
        'name','title','title_color','image','tour_type','tour_code','display','post','link','language','description','displayorder','status','payment_status'
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    public static $status = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'In Active',
    ];


    const Payment_STATUS_PAID = 'Paid';
    const Payment_STATUS_NOTPAID = 'NotPaid';

    public static $payment_status = [
        self::Payment_STATUS_PAID => 'Paid',
        self::Payment_STATUS_NOTPAID => 'Not Paid',
    ];


    const LANGUAGE_ENG = 'English';
    const LANGUAGE_繁中 = '繁中';
    const LANGUAGE_簡= '簡';

    public static $language = [
        self::LANGUAGE_ENG => 'English',
        self::LANGUAGE_繁中 => '繁中',
        self::LANGUAGE_簡 => '簡',
    ];

    const POST_Public = 'Public';


    public static $post = [
        self::POST_Public => 'Public',
       
    ];

    const POST_Private = 'Private';

    public static $post1 = [
        self::POST_Private => 'Private',

    ];

    const DISPLAY_FullScreen = 'Full Screen';
    const DISPLAY_Simplified = 'Simplified';

    public static $display = [
        self::DISPLAY_FullScreen => 'Full Screen',
        self::DISPLAY_Simplified => 'Simplified',
    ];

    const TYPE_Normal = 'Normal';
    const TYPE_Disneyland = 'Disneyland';
    const TYPE_Op= 'Ocean park';
    const TYPE_Turbojet = 'Turbojet';

    public static $type = [
        self::TYPE_Normal => 'Normal',
        self::TYPE_Disneyland => 'Disneyland',
        self::TYPE_Op => 'Ocean park',
        self::TYPE_Turbojet => 'Turbojet',
    ];

    public function Tourhighlight()
    {
        return $this->hasMany('App\Tourhighlight', 'detailid');
    }

    public function Tourcheckpoint()
    {
        return $this->hasMany('App\Tourcheckpoint', 'detailid');
    }

    public function TourPrice()
    {
        return $this->hasMany('App\TourPrice', 'detailid');
    }

    public function TourPricegroup()
    {
        return $this->hasMany('App\TourPricegroup', 'detailid');
    }

    public function TourInventory()
    {
        return $this->hasMany('App\TourInventory', 'detailid');
    }

    public function isDisneylandType()
    {
        return in_array(TourList::TYPE_Disneyland, explode(',', $this->tour_type));
    }

    public function isOceanParkType()
    {
        return in_array(TourList::TYPE_Op, explode(',', $this->tour_type));
    }

    public function isTurbojetType()
    {
        return in_array(TourList::TYPE_Turbojet, explode(',', $this->tour_type));
    }

    public function getImageThumbAttribute()
    {
        return ImageHelper::getThumbByOriginal($this->image);
    }
}
