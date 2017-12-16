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
    const MySqlDateTimeFormat = 'Y-m-d H:i:s';
    const Equal = 0;
    const Before = -1;
    const After = 1;

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

    public static function formatMySqlDate($dateString) {
        if (empty($dateString)) {
            return null;
        }
        $formatted = self::reformatDateTime($dateString,self::MySqlDateFormat);
        if ($formatted == '0000-00-00') {
            return null;
        }
        return $formatted;
    }

    public static function today($format=self::MySqlDateFormat) {
        return date($format);
    }

    public static function now($format=self::MySqlDateTimeFormat) {
        return date($format);
    }

    const returnTypeMySqlString = 1;
    const returnTypeDateObject = 2;
    const returnTypeCorrectedDate = 3;
    const returnTypeTimeStamp = 4;
    const returnTypeObject = 5;
    const returnTypeInterval = 1;
    const returnTypeInterSpec = 2;


    /**
     * Validates and converts a date string according to the $returnType parameter
     * Valid formats are restricted to m/d/Y and Y-m-d (mysql) with a four-digit year
     *
     * @param $date string
     * @param int $returnType
     * @return bool|DateTime|false|int|null|string
     *
     * Use TDates::returnType... constants to indicate return type of
     *      MySql string, Date object, timestamp or corrected dates.
     *      Corrected dates add leading zeroes on all single digit values.
     *
     * Time parts are ignored and not included in the return value.
     */
    public static function getValidDate($date,$returnType = 1) {

        $date = $date==null ? '' : trim(explode(' ',$date)[0]);  // trim off time part
        if (empty($date)) {
            return $returnType == self::returnTypeDateObject ? null : '';
        }

        $formats = ['Y-m-d','d/m/Y'];
        $originalFormat = false;
        foreach ($formats as $format) {
            $dateTime = DateTime::createFromFormat($format, $date);
            if ($dateTime !== false) {
                $sep = substr($format, 1, 1);
                $parts = explode($sep, $date);
                $y = strpos($format, 'Y') / 2;
                $year = $parts[$y];
                if (strlen($year) != 4) {
                    return false;
               }
                for ($i = 0; $i < 3; $i++) {
                    if ($i != $y && strlen($parts[$i]) == 1) {
                        $parts[$i] = '0' . $parts[$i];
                    }
                }
                $date = join($sep,$parts);
                if ($dateTime->format($format) == $date) {
                    $originalFormat = $format;
                    break;
                }
            }
        }

        if ($originalFormat === false) {
            return false;
        }

        switch ($returnType) {
            case self::returnTypeDateObject :
                return new \DateTime($date);
            case self::returnTypeCorrectedDate :
                return $date;
            case self::returnTypeTimeStamp:
                return @strtotime($date);
            default : // MySql string
                return $originalFormat === self::MySqlDateFormat ? $date :
                    self::reformatDateTime($date,self::MySqlDateFormat,$originalFormat);
        }
    }

    /**
     * @param $intervalString
     * @return bool|\DateInterval
     *
     * intervalString must be in the form of a count and a unit expressed as a string starting with the letter of the
     * unit part of an interval_spec (see http://php.net/manual/en/dateinterval.construct.php) or 'MI' for minutes
     * this is not case sensitive.  Examples:
     * '10 days', '2 years', '30 minutes', '7 months','30 seconds'
     * Note that '7 m' == '7 months', '7 mi' == '7 minutes'
     *
     * Complex intervals like one hour and thirty minutes are not supported.
     */
    public static function StringToInterval($intervalString)
    {
        $parts = array_filter(explode(' ',$intervalString));
        if (sizeof($parts) != 2
            || (!is_numeric($parts[0]))
        ) {
            return false;
        }
        $count = $parts[0];
        $unit = strtoupper($parts[1]);
        
        if (substr($unit, 0, 2) == 'MI') {
            $prefix = 'PT';
            $unit = 'M';
        }
        else {
            $unit = substr($unit, 0, 1);
            if (strpos('YMDWHS',$unit) === false) {
                return false;
            }
            $prefix =  $unit == 'S' || $unit == 'H' ? 'PT' : 'P';
        }
        return new \DateInterval($prefix.$count.$unit);
    }

    /**
     * Increment the first date and compare with the second.
     *
     * @param $date1  - First (left hand) date to copare
     * @param $offset  - An interval string such as '2 days', '90 seconds' etc. see StringToInterval used to
     *                   increment $date1.  Ignored if empty.
     * @param null $date2 - Second (right hand date) to compare. Defaults to current date/time
     * @return bool|int  - False on failure else const TDates::Before (-1), TDates::After (1) or TDates::Equal (0)
     */
    public static function CompareDates($date1,$offset,$date2=null) {
        // reformat to ISO dates for consistent results;
        $date1 = TDates::reformatDateTime($date1,DateTime::ATOM);
        if ($date2 !== null) {
            $date2 = TDates::reformatDateTime($date2,DateTime::ATOM);
        }
        try {
            $left = new \DateTime($date1);
            $right = $date2 === null ?
                new \DateTime() :
                new \DateTime($date2);
        }
        catch (\Exception $ex) {
            return false;
        }

        if (!empty($offset)) {
            $interval = self::StringToInterval($offset);
            if ($interval === false) {
                return false;
            }
            $left->add($interval);
        }
        $dif = $right->diff($left);

        // all elements except micro-seconds are equal
        if ($dif->format("%R%Y%M%D%H%I%S") === '+000000000000') {
            return 0;
        }

        /*
         * If a DateInterval object was returned, you can check the 'invert' property to see if the second date is
         * before the first date or not. DateInterval::invert will be 1 if the second date is before the first date,
         * and 0 if the the second date is on or after the first date.
         */
        return $dif->invert == 0 ? TDates::After : TDates::Before;
    }

    /**
     * CompareDates with no offset
     *
     * @param $date1
     * @param null $date2
     * @return bool|int
     */
    public static function CompareTwoDates($date1,$date2=null) {
        return self::CompareDates($date1,null,$date2);
    }
    /**
     * CompareDates with no offset
     *
     * @param $date1
     * @param null $date2
     * @return bool|int
     */
    public static function CompareWithNow($date1) {

        return self::CompareDates($date1,null,null);
    }

}