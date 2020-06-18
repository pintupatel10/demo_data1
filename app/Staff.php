<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use SoftDeletes;
    protected $table="users";
    protected $fillable = [
        'name','email', 'image','number', 'password','group_id','user_id','status','role','actionlog_show'
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    public static $status = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'In Active',
    ];


    const Action_Yes = 'Yes';
    const Action_No = 'No';

    public static $actionlog = [
        self::Action_Yes => 'Yes',
        self::Action_No => 'No',
    ];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function Group()
    {
        return $this->belongsTo('App\Group', 'group_id');
    }
    
}
