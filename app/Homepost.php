<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Homepost extends Model
{
    use SoftDeletes;
    protected $table="home_posts";
    protected $fillable = [
        'title','language','image_upload','image_position','description','displayorder','status'
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    public static $status = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'In Active',
    ];


    const POSITION_LEFT = 'left';
    const POSITION_RIGHT = 'right';

    public static $position = [
        self::POSITION_LEFT => 'Left',
        self::POSITION_RIGHT => 'Right',
    ];


    const LANGUAGE_ENG = 'English';
    const LANGUAGE_繁中 = '繁中';
    const LANGUAGE_簡= '簡';

    public static $language = [
        self::LANGUAGE_ENG => 'English',
        self::LANGUAGE_繁中 => '繁中',
        self::LANGUAGE_簡 => '簡',
    ];


    const LANGUAGE1_ALL = 'All';
    const LANGUAGE1_ENG = 'English';
    const LANGUAGE1_繁中 = '繁中';
    const LANGUAGE1_簡= '簡';

    public static $language1 = [
        self::LANGUAGE1_ALL => 'All',
        self::LANGUAGE1_ENG => 'English',
        self::LANGUAGE1_繁中 => '繁中',
        self::LANGUAGE1_簡 => '簡',
    ];
}
