<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransportationTimetable extends Model
{
    use SoftDeletes;
    protected $table="transportation_timetables";
    protected $fillable = [
        'detailid','pricegroupid','alltime','time','Weekend/Weekday','displayorder','status'
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    public static $status = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'In Active',
    ];


    const TYPE_Weekend = 'Weekend';
    const TYPE_Weekday = 'Weekday';
    const TYPE_Weekend_Weekday= 'Weekend & Weekday';

    public static $type = [
        self::TYPE_Weekend => 'Weekend',
        self::TYPE_Weekday => 'Weekday',
        self::TYPE_Weekend_Weekday => 'Weekend & Weekday',
    ];

    const POST_Public = 'All time';
    const POST_Private = 'Specific time';

    public static $post = [
        self::POST_Public => 'All time',
        self::POST_Private => 'Specific time',
    ];
    
    public function TransportationList()
    {
        return $this->belongsTo('App\TransportationList', 'id');
    }

    public function TransportationPricegroup()
    {
        return $this->belongsTo('App\TransportationPricegroup', 'id');
    }
}
