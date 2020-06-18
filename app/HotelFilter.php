<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HotelFilter extends Model
{
    use SoftDeletes;
    protected $table="hotel_filters";
    protected $fillable = [
        'cid','name','hotel_list','displayorder','status'
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    public static $status = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'In Active',
    ];

    public function Hotelcollection()
    {
        return $this->belongsTo('App\Hotelcollection', 'id');
    }
}
