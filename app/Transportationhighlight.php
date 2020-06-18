<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transportationhighlight extends Model
{
    use SoftDeletes;
    protected $table="transportation_highlights";
    protected $fillable = [
        'detailid','title','content'
    ];

    public function TransportationList()
    {
        return $this->belongsTo('App\TransportationList', 'id');
    }
}
