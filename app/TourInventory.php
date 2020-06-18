<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TourInventory extends Model
{
    use SoftDeletes;
    protected $table="tour_inventorys";
    protected $fillable = [
        'detailid','pricegroupid','title','dquota','displayorder','status'
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    public static $status = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'In Active',
    ];

    public function TourList()
    {
        return $this->belongsTo('App\TourList', 'id');
    }

    public function TourPricegroup()
    {
        return $this->belongsTo('App\TourPricegroup', 'id');
    }

    public function TourPrice()
    {
        return $this->belongsTo('App\TourPrice', 'title');
    }

    public function Quota()
    {
        return $this->hasMany('App\Quota', 'quotaid');
    }

}
