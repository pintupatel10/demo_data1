<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuType extends Model
{
    protected $fillable = [
        'id','menu_id','language','list_id',
        //'news_id','service_id','hotel_id','contactus_id','transportation_id','tour_id','ticket_id',
    ];
    const LANGUAGE_ENG = 'English';
    const LANGUAGE_繁中 = '繁中';
    const LANGUAGE_簡= '簡';

    public static $language = [
        self::LANGUAGE_ENG => 'English',
        self::LANGUAGE_繁中 => '繁中',
        self::LANGUAGE_簡 => '簡',
    ];

    public function Menu(){
        return $this->belongsTo('App\Menu','id');
    }

}
