<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailList extends Model
{
    protected $fillable = [
        'id','advertise_id','email',
    ];
    public function EmailAdvertise(){
        
        return $this->belongsTo('App\EmailAdvertise','advertise_id');
    }
}
