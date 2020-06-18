<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tourcheckpoint extends Model
{
    use SoftDeletes;
    protected $table="tour_checkpoints";
    protected $fillable = [
        'detailid','title','image','description','displayorder','status','checkpoint_id',
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
}
