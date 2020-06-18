<?php

namespace App;

use App\Helpers\ImageHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransportationGroup extends Model
{
    use SoftDeletes;
    protected $table="transportation_groups";
    protected $fillable = [
        'portrait_image','landscape_image','title','language','description','transportation_list',
        'displayorder','status','select_sentence',
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

    public function getPortraitImageThumbAttribute()
    {
        return ImageHelper::getThumbByOriginal($this->portrait_image);
    }
}
