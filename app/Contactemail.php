<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contactemail extends Model
{
    use SoftDeletes;
    protected $table="contact_emails";
    protected $fillable = [
        'contactid','email_receiver'
    ];

    public function Contactus()
    {
        return $this->belongsTo('App\Contactus', 'id');
    }
}
