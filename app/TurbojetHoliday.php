<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TurbojetHoliday extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_DATE = 'date';

    protected $fillable = [
        self::COLUMN_DATE,
    ];

    protected $dates = [
        self::COLUMN_DATE,
    ];
}
