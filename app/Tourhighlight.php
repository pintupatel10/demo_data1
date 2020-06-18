<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tourhighlight extends Model
{
    use SoftDeletes;
    protected $table="tour_highlights";
    protected $fillable = [
        'detailid','title','content'
    ];

    public function TourList()
    {
        return $this->belongsTo('App\TourList', 'id');
    }

}
