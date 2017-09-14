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
    const MySqlDateFormat = 'Y-m-d';
    const MySqlDateTimeFormat = 'Y-m-d H:i:s';

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
            $originalFormat = self::MySqlDateTimeFormat;
        }
        $dateobj = @DateTime::createFromFormat($originalFormat, $timeString);
        if (!empty($dateobj)) {
            $timeString = $dateobj->format(Datetime::ATOM);
        }
        $time = @strtotime($timeString);
        return $time;
    }

    public static function formatDate($timestamp=null, $format=self::MySqlDateFormat) {
        return date($format,$timestamp);
    }

    public static function formatDateTime($timestamp=null, $format=self::MySqlDateTimeFormat) {
        return date($format,$timestamp);
    }

    public static function today($format=self::MySqlDateFormat) {
        return date($format);
    }

    public static function now($format=self::MySqlDateTimeFormat) {
        return date($format);
    }

}