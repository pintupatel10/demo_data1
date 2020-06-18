<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'id','displayorder','title','status','created_at','updated_at',
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    public static $status = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'In Active',
    ];
    public function MenuType(){
        return $this->hasMany('App\MenuType','menu_id');
    }

    public static function boot()
    {
        static::deleted(function($model) {
            foreach ($model->MenuType as $menutype)
                $menutype->delete();
        });
        parent::boot();
    }

}
