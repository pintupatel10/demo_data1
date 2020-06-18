<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contactrecord extends Model
{
    protected $table="contacted_records";
    protected $fillable = [
        'follow_up','title','lastname','firstname','email','telephone','address','fax_no','country','message'
    ];
}
