<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MailAddress extends Model
{
    use SoftDeletes;
    protected $table="mail_addresses";
    protected $fillable = [
        'emailid','mail_address'
    ];

    public function Emailset()
    {
        return $this->belongsTo('App\Emailset', 'id');
    }
}
