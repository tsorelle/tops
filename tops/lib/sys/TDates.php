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
    const FilenameTimeFormat = 'Y-m-d@H-i-s';
    const Equal = 0;
    const Before = -1;
    const After = 1;
    const DowNames = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];

    public static function reformatDateTime($timeString, $newFormat, $originalFormat = null)
    {
        $time = self::stringToTimestamp($timeString, $originalFormat);
        if ($time === false) {
            return $timeString;
        }
        return date($newFormat, $time);
    }

    public static function stringToTimestamp($timeString, $originalFormat = null)
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

    public static function formatDate($timestamp = null, $format = self::MySqlDateFormat)
    {
        return date($format, $timestamp);
    }

    public static function formatDateTime($timestamp = null, $format = self::MySqlDateTimeFormat)
    {
        return date($format, $timestamp);
    }

    public static function formatMySqlDate($dateString, $includeTime = false)
    {
        if (empty($dateString)) {
            return null;
        }
        $format = $includeTime ? self::MySqlDateTimeFormat : self::MySqlDateFormat;
        $formatted = self::reformatDateTime($dateString, $format);
        if (is_string($formatted) ){
            if (substr($formatted, 0, 10) == '0000-00-00') {
                return null;
            }
            if (strtotime($dateString) === false) {
                return false;
            }
        }
        return $formatted;
    }


    public static function today($format = self::MySqlDateFormat)
    {
        return date($format);
    }

    public static function now($format = self::MySqlDateTimeFormat)
    {
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
    public static function getValidDate($date, $returnType = 1)
    {

        $date = $date == null ? '' : trim(explode(' ', $date)[0]);  // trim off time part
        if (empty($date)) {
            return $returnType == self::returnTypeDateObject ? null : '';
        }

        $formats = ['Y-m-d', 'd/m/Y'];
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
                $date = join($sep, $parts);
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
                    self::reformatDateTime($date, self::MySqlDateFormat, $originalFormat);
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
        $parts = array_filter(explode(' ', $intervalString));
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
        } else {
            $unit = substr($unit, 0, 1);
            if (strpos('YMDWHS', $unit) === false) {
                return false;
            }
            $prefix = $unit == 'S' || $unit == 'H' ? 'PT' : 'P';
        }
        return new \DateInterval($prefix . $count . $unit);
    }

    /**
     * Increment the first date and compare with the second.
     *
     * @param $date1 - First (left hand) date to copare
     * @param $offset - An interval string such as '2 days', '90 seconds' etc. see StringToInterval used to
     *                   increment $date1.  Ignored if empty.
     * @param null $date2 - Second (right hand date) to compare. Defaults to current date/time
     * @return bool|int  - False on failure else const TDates::Before (-1), TDates::After (1) or TDates::Equal (0)
     */
    public static function CompareDates($date1, $offset, $date2 = null)
    {
        // reformat to ISO dates for consistent results;
        $date1 = TDates::reformatDateTime($date1, DateTime::ATOM);
        if ($date2 !== null) {
            $date2 = TDates::reformatDateTime($date2, DateTime::ATOM);
        }
        try {
            $left = new \DateTime($date1);
            $right = $date2 === null ?
                new \DateTime() :
                new \DateTime($date2);
        } catch (\Exception $ex) {
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
    public static function CompareTwoDates($date1, $date2 = null)
    {
        return self::CompareDates($date1, null, $date2);
    }

    /**
     * CompareDates with no offset
     *
     * @param $date1
     * @return bool|int
     */
    public static function CompareWithNow($date1)
    {
        return self::CompareDates($date1, null, null);
    }

    public static function GetMySqlDateProperty($object, $propertyName, $includeTime = false)
    {
        if (property_exists($object, $propertyName)) {
            $result = TDates::formatMySqlDate($object->$propertyName, $includeTime);
            return $result;
        }
        return false;
    }

    public static function isValidDateString($dateString) {
        return strtotime($dateString) !== false;
    }

    public static function buildCalendar($assignFunction,$month=null,$year=null,$format='Y-m-d') {
        if ($month === null ) {
            $month = date('m');
        }
        if ($year === null) {
            $year = date('Y');
        }
        $monthStart = strtotime(sprintf('%d-%d-1',$year,$month));
        $monthEnd = strtotime('last day of this month', $monthStart);// time());
        $date = date('N', $monthStart) == 7 ? $monthStart : strtotime('last Sunday', $monthStart);
        $calEnd = date('N', $monthEnd) == 6 ? strtotime('+ 1 day',$monthEnd) : strtotime('next Sunday', $monthEnd);
        $result = [];
        while ($date != $calEnd) {
            $datestr = date($format,$date);
            $result[$datestr] = $assignFunction($date);
            $date =  strtotime('+ 1 day',$date);
        }

        return $result;
    }

    /**
     * @param $year
     * @param $month
     * @param $dayofweek -- valid date format string e.g.  'Wed','wed','Wednesday' or 'wednesday' or number where 1=sunday
     */
    public static function getWeekDates($year,$month,$dayofweek) {
        if (is_numeric($dayofweek)) {
            if ($dayofweek < 1 || $dayofweek > 7) {
                // invalid input
                return false;
            }
            // since October 2017 started on sunday we can get day of week from format
            $dayofweek = date('D', mktime(0, 0, 0, 10, $dayofweek, 2017));
        }
        $ordinals = ['first', 'second', 'third', 'fourth', 'fifth'];
        $date = new DateTime(sprintf("%d-%d-1", $year, $month));
        $date->modify(sprintf("last %s of this month", $dayofweek));
        $last = clone $date;
        $result = [];
        foreach ($ordinals as $ordinal) {
            $date->modify(sprintf("%s %s of this month", $ordinal, $dayofweek));
            $result[] = $date->format('Y-m-d');
            if ($date == $last) {
                break;
            }
        }
        return $result;
    }

    public static function getDatesInRange($startDate,$endDate,$options=null) {
        $weekDays = !empty($options->weekDays);
        $skip = empty($options->skip) ? 1 : $options->skip;
        $endDate=new DateTime($endDate);
        $result = [];
        $current = new DateTime($startDate);
        while ($current < $endDate) {
            $dow = $weekDays ? $dow = $current->format('D') : '' ;
            if ($dow != 'Sat' && $dow != 'Sun') {
                $result[] = $current->format('Y-m-d');;
            }
            $current->modify("+ $skip days");
        }
        return $result;
    }
    
    public static function GetCalendarMonth($year,$month,$pageDirection='')
    {
        $result = new \stdClass();
        $startDate = new DateTime(sprintf('%d-%d-1', $year, $month));
        $result->monthDate = clone $startDate;
        $endDate = clone $startDate;
        $endDate->modify('last day of this month');
        if ($startDate->format('D') != 'Sun') {
            $startDate->modify('last sun of last month');
        }
        if ($endDate->format('D') != 'Sat') {
            $endDate->modify('first sat of next month');
        }
        $endDate->modify('+ 1 day');
        if ($pageDirection =='left') {
            $endDate->modify('-7 days');
        }
        else if ($pageDirection == 'right') {
            $startDate->modify('+7 days');
        }

        $result->start = $startDate->format('Y-m-d');
        $result->end = $endDate->format('Y-m-d');
        return $result;
    }

    public static function DowListToArray($days) {
        $count = strlen($days);
        $result = [];
        if ($count > 7) {
            for ($i = 0; $i < $count; $i++) {
                // since October 2017 started on sunday we can get day of week from format
                $result[] = date('D', mktime(0, 0, 0, 10, $days{$i}, 2017));
            }
        }
        return $result;
    }

    public static function GetDowName($n) {
        return self::DowNames[$n-1];
    }
    public static function GetDowNumber($s) {
        return array_search($s,self::DowNames) + 1;
    }


    public static function SetDowThisWeek(DateTime $date,$dow) {
        if (!is_numeric($dow)) {
            $dow = self::GetDowNumber($dow);
        }
        $currentDow = self::GetDowNumber($date->format('D'));
        if ($currentDow !== $dow) {
            $dif = $dow - $currentDow;
            $adjustment = sprintf('%s %d days',$dif < 0 ? "-" : '+',abs($dif));
            $date->modify($adjustment);
        }
    }

    /**
     * @param DateTime $date
     * @param $dow
     * @param string $format
     * @return DateTime
     */
    public static function GetDowThisWeek(DateTime $date,$dow,$format='Y-m-d') {
        $result = clone $date;
        self::SetDowThisWeek($result,$dow);
        return $result;
    }

    /**
     * @param $date
     * @return bool|DateTime
     */
    public static function CreateDateTime($date) {
        $parts = explode('-',$date);
        $date= sprintf("%d-%02d-%02d", $parts[0], $parts[1], $parts[2]);
        $d = DateTime::createFromFormat('Y-m-d', $date);
        if ($d->format('Y-m-d') === $date) {
            return $d;
        };
        return false;
    }

}