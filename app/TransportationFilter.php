<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransportationFilter extends Model
{
    use SoftDeletes;
    protected $table="transportation_filters";
    protected $fillable = [
        'cid','name','group_list','transportation_list','displayorder','status'
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    public static $status = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'In Active',
    ];

    public function Transportationcollection()
    {
        return $this->belongsTo('App\Transportationcollection', 'id');
    }
}
