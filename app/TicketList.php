<?php

namespace App;

use App\Helpers\ImageHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketList extends Model
{
    use SoftDeletes;
    protected $table="ticket_lists";
    protected $fillable = [
        'title','title_color','image','ticket_type','ticket_code','display','post','link','language','description','displayorder','status'
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

    const POST_Public = 'Public';

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
    const TYPE_Disneyland = 'Disneyland';
    const TYPE_Op= 'Ocean park';

    public static $type = [
        self::TYPE_Normal => 'Normal',
        self::TYPE_Disneyland => 'Disneyland',
        self::TYPE_Op => 'Ocean park',
    ];

    public function Ticketcheckpoint()
    {
        return $this->hasMany('App\Ticketcheckpoint', 'detailid');
    }

    public function Tickethighlight()
    {
        return $this->hasMany('App\Tickethighlight', 'detailid');
    }

    public function TicketPricegroup()
    {
        return $this->hasMany('App\TicketPricegroup', 'detailid');
    }

    public function TicketPrice()
    {
        return $this->hasMany('App\TicketPrice', 'detailid');
    }
    

    public function TicketVolume()
    {
        return $this->hasMany('App\TicketVolume', 'detailid');
    }

    public function isDisneylandType()
    {
        return in_array(TicketList::TYPE_Disneyland, explode(',', $this->ticket_type));
    }

    public function isOceanParkType()
    {
        return in_array(TicketList::TYPE_Op, explode(',', $this->ticket_type));
    }

    public function getImageThumbAttribute()
    {
        return ImageHelper::getThumbByOriginal($this->image);
    }
}
