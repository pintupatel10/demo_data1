<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransportationInventory extends Model
{
    use SoftDeletes;
    protected $table="transportation_inventorys";
    protected $fillable = [
        'detailid','pricegroupid','title','dquota','displayorder','status'
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    public static $status = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'In Active',
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
