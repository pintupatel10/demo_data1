<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TourFilter extends Model
{
    use SoftDeletes;
    protected $table="tour_filters";
    protected $fillable = [
        'cid','name','group_list','tour_list','displayorder','status'
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    public static $status = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'In Active',
    ];

    public function Tourcollection()
    {
        return $this->belongsTo('App\Tourcollection', 'id');
    }
}
