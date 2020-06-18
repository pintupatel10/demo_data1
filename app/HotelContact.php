<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HotelContact extends Model
{
    protected $fillable = [
        'title','lastname','firstname','email','telephone','address','fax_no','country','message'
    ];
}
