<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nationality extends Model
{
    const COLUMN_EN = "en";
    const COLUMN_ZH_HK = "zh_hk";
    const COLUMN_ZH_CN = "zh_cn";

    public static function getList()
    {
        $locale = \App::getLocale();
        if ($locale == "en")
            $column = self::COLUMN_EN;
        else if ($locale == "zh-hk")
            $column = self::COLUMN_ZH_HK;
        else
            $column = self::COLUMN_ZH_CN;

        $list = [];
        foreach (Nationality::orderBy($column)->get() as $nationality)
        {
            $list[$nationality->id] = $nationality->{$column};
        }
        return $list;
    }

    public function getNameAttribute()
    {
        return $this->getNameOfLocale(\App::getLocale());
    }

    public function getNameOfLocale($locale)
    {
        if ($locale == "en")
            $column = self::COLUMN_EN;
        else if ($locale == "zh-hk")
            $column = self::COLUMN_ZH_HK;
        else
            $column = self::COLUMN_ZH_CN;

        return $this->{$column};
    }
}
