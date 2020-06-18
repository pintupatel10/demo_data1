<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailAdvertise extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'id','subject','content','sendingdate','no_of_mail','status',
    ];
    public function EmailList(){

        return $this->hasMany('App\EmailList','advertise_id');
    }

    public static function boot()
    {
        static::deleted(function($model) {

            foreach ($model->EmailList as $emaillist)
                $emaillist->delete();
        });
        parent::boot();
    }
    
}
