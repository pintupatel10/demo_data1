<?php
/**
 * Created by PhpStorm.
 * User: yeelok
 * Date: 22/3/2017
 * Time: 22:58
 */

namespace App\Helpers;

use Image;


class ImageHelper
{
    public static function generateThumbnail($path)
    {
        if (!is_null($path) && strlen($path) > 0 && file_exists(base_path() . '/public/' . $path) && is_file(base_path() . '/public/' . $path))
        {
            $info = pathinfo($path);
            $thumbnail_path = $info['dirname'] . '/' . $info['filename'] . '-thumb.' . $info['extension'];

            Image::make(base_path() . '/public/' . $path)->widen(500, function ($constraint) {
                $constraint->upsize();
            })->save(base_path() . '/public/' . $thumbnail_path);
        }
    }

    public static function getThumbByOriginal($path)
    {
        if (is_null($path) || strlen($path) == 0)
            return null;

        $info = pathinfo($path);
        $thumb = $info['dirname'] . '/' . $info['filename'] . '-thumb.' . $info['extension'];
        if (file_exists(base_path() . '/public/' . $thumb) && is_file(base_path() . '/public/' . $thumb))
            return $thumb;
        else
            return $path;
    }
}