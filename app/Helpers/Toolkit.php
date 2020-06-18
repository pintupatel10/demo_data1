<?php

namespace App\Helpers;


class Toolkit
{
    const STR_azAZ09 = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const STR_az09 = 'abcdefghijklmnopqrstuvwxyz1234567890';
    const STR_09 = '0123456789';
    const STR_AZ09 = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * Get microtimestamp with 5 random integer for minimise timestamp collision
     * @return int
     */
    static public function getUniqueTimestamp()
    {
        return sprintf("%d%s", microtime(true) * 10000, self::mt_rand_str(5, '1234567890'));
    }

    static public function mt_rand_str($l, $c = Toolkit::STR_az09) {
        for ($s = '', $cl = strlen($c)-1, $i = 0; $i < $l; $s .= $c[mt_rand(0, $cl)], ++$i);
        return $s;
    }

    public static function stringIsInteger($value)
    {
        return preg_match('/^\d+$/', $value);
    }
}