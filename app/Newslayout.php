<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Newslayout extends Model
{
    use SoftDeletes;
    protected $table="news_layouts";
    protected $fillable = [
        'menu_name','title','image','language','displayorder','status'
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    public static $status = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'In Active',
    ];

    const LANGUAGE_ENG = 'English';
    const LANGUAGE_繁中 = '繁中';
    const LANGUAGE_簡= '簡';

    public static $language = [
        self::LANGUAGE_ENG => 'English',
        self::LANGUAGE_繁中 => '繁中',
        self::LANGUAGE_簡 => '簡',
    ];
}
