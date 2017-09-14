<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 9/14/2017
 * Time: 9:30 AM
 */

namespace Tops\sys;


use DateTime;

class TDates
{

    public static function reformatDateTime($timeString, $newFormat, $originalFormat=null)
    {
        $time = self::stringToTimestamp($timeString,$originalFormat);
        if ($time === false) {
            return $timeString;
        }
        return date($newFormat, $time);
    }

    public static function stringToTimestamp($timeString, $originalFormat=null)
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
        return $time;
    }

    public static function formatDate($timestamp=null, $format='Y-m-d') {
        return date($format,$timestamp);
    }

    public static function formatDateTime($timestamp=null, $format='Y-m-d H:i:s') {
        return date($format,$timestamp);
    }

    public static function today($format='Y-m-d') {
        return date($format);
    }

    public static function now($format='Y-m-d H:i:s') {
        return date($format);
    }

}