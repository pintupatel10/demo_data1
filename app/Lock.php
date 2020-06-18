<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Lock extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_NAME = 'name';
    const COLUMN_DATA = 'data';
    const COLUMN_LAST_LOCKED_AT = 'last_locked_at';

    const LOCK_CHECKOUT = 'checkout';

    protected $fillable = [
        self::COLUMN_NAME,
        self::COLUMN_DATA
    ];

    protected $dates = [
        self::COLUMN_LAST_LOCKED_AT,
    ];

    public static function getLock($name, $default_data = "")
    {
        $lock = Lock::where(self::COLUMN_NAME, $name)->first();
        if (!$lock)
            $lock = Lock::create([self::COLUMN_NAME => $name, self::COLUMN_DATA => $default_data]);
        return $lock;
    }

    public function lock()
    {
        $new_instance = Lock::lockForUpdate()->find($this->{self::COLUMN_ID});
        $new_instance->{self::COLUMN_LAST_LOCKED_AT} = Carbon::now();
        $new_instance->save();

        return $new_instance;
    }
}
