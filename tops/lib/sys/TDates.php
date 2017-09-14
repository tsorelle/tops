<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 9/14/2017
 * Time: 9:30 AM
 */

namespace Tops\sys;


class TDates
{
    private static function reformatDateTime($timeString, $newFormat, $originalFormat=null)
    {
        if (empty($originalFormat)) {
            // assume mysql format
            $originalFormat = 'Y-m-d H:i:s';
        }
        $dateobj = @DateTime::createFromFormat($originalFormat, $timeString);
        if (!empty($dateobj)) {
            $timeString = $dateobj->format(Datetime::ATOM);
        }
        $time = @strtotime($timeString);
        if ($time === false) {
            return $timeString;
        }
        return date($newFormat, $time);
    }
}