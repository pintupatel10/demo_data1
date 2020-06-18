<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailCollect extends Model
{
    protected $fillable = [
        'id','title','email','name',
    ];
}
