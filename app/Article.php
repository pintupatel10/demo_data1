<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogsActivity;
use Spatie\Activitylog\LogsActivityInterface;

class Article extends Model implements LogsActivityInterface
{
    use LogsActivity;
    protected $table="activity_log";
    protected $fillable = [
        'id','user_id', 'text','ip_address'
    ];

    public function Staff()
    {
        return $this->belongsTo('App\Staff','id');
    }


    public function getActivityDescriptionForEvent($eventName)
    {
        if ($eventName == 'created')
        {
            return 'Article "' . $this->name . '" was created';
        }

        if ($eventName == 'updated')
        {
            return 'Article "' . $this->name . '" was updated';
        }

        if ($eventName == 'deleted')
        {
            return 'Article "' . $this->name . '" was deleted';
        }

        return '';
    }
}
