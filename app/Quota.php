<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quota extends Model
{
    use SoftDeletes;
    protected $table="quotas";
    protected $fillable = [
        'quotaid','quota','date','to','day_of_week'
    ];


    public function TourPrice()
    {
        return $this->belongsTo('App\TourPrice', 'quotaid');
    }

    public function getCarbonDayOfWeek()
    {
        if ($this->day_of_week == 'mon')        return Carbon::MONDAY;
        if ($this->day_of_week == 'tue')        return Carbon::TUESDAY;
        if ($this->day_of_week == 'wed')        return Carbon::WEDNESDAY;
        if ($this->day_of_week == 'thu')        return Carbon::THURSDAY;
        if ($this->day_of_week == 'fri')        return Carbon::FRIDAY;
        if ($this->day_of_week == 'sat')        return Carbon::SATURDAY;
        if ($this->day_of_week == 'sun')        return Carbon::SUNDAY;
        return null;
    }

}
