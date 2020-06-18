<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_USER_ID = 'user_id';
    const COLUMN_EVENT = 'event';
    const COLUMN_AUDITABLE_TYPE = 'auditable_type';
    const COLUMN_AUDITABLE_ID = 'auditable_id';
    const COLUMN_OLD_VALUES = 'old_values';
    const COLUMN_NEW_VALUES = 'new_values';
    const COLUMN_URL = 'url';
    const COLUMN_IP_ADDRESS = 'ip_address';
    const COLUMN_REMARK = 'remark';

    protected $guarded = [];

    public function auditable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
