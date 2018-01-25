<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 1/22/2018
 * Time: 6:14 AM
 */

namespace Tops\sys;


use DateTime;

class TDateRepeater
{

    /**
     * @param $year
     * @param $month
     * @param $repeatSpec
     * @return array | bool
     *
     * $repeatSpec string format:
     *    Convention
     *    	[pattern-string];[range-string (optional)
     *    Pattern
     *    	conventions
     *    		two char type+spec
     *    		no separator for single char values
     *    		comma separator for multi-char values
     *    		in day spec 1=sunday..5=last
     *    		if no ambiguity, no separator between series of single-char values
     *
     *    	Daily
     *    		every x days | every weekday
     *    			dd30	   every thirty days
     *    			dw			every weekday
     *    	weekly
     *    		every x weeks + set of weekdays
     *    			wk3,34		every three weeks on tues and wednesday
     *
     *    	monthly
     *    		day x of y months | Ord day of every x months
     *    			md23,2		day 23 of every one two months
     *    			mo323		third tues every three months
     *    	yearly
     *    		every x years on date | every ord dayofweek in month
     *    			yd2,2,32	Every 2 years on Feb 32
     *    			yo4311		4th tues in November
     *    Range
     *    	start-date + no-end, after-x, by-date
     *
     *    	[pattern-string]2017-09-2			        Start 2017-09-2, no end
     *    	[pattern-string];2017-09-2,10		        Start 2017-09-2, end after 10 times
     *    	[pattern-string];start 2017-09-2,2018-2-12	Start 2017-09-2, End by Feb 12, 2018-2-12
     */
    public function getDates($year,$month,$repeatSpec,$pageDirection='') {
        $calendarPage = TDates::GetCalendarMonth($year,$month,$pageDirection);

        @list($pattern,$range) = explode(';',$repeatSpec);
        if (empty($range)) {
            return false;
        }
        $patternType = substr($pattern,0,2);
        $pattern = substr($pattern,2);
        @list($startDate,$endDate) = explode(',',$range);
        $startDate = date('Y-m-d',strtotime($startDate));
        if ($startDate >= $calendarPage->end){
            return [];
        }

        if (is_numeric($endDate)) {
            $endDate = $this->calculateEndDate($startDate,$endDate, $pattern);
        }

        if (!empty($endDate)) {
            if ($endDate <= $calendarPage->start ) {
                return [];
            }
            if ($endDate <=  $calendarPage->end) {
                $calendarPage->end = $endDate;
            }
        }

        switch($patternType) {
            case 'dd' :
                return $this->getDaysSinceStart($startDate,$calendarPage, $pattern);
                break;
            case 'dw' :
                return $this->getWeekDays($startDate,$calendarPage, $pattern);
                break;
            case 'wk' :
                return $this->getWeekly($startDate, $calendarPage, $pattern);
                break;
            case 'md' :
                return $this->monthDates($month,$year,$startDate, $calendarPage, $pattern);
                break;
            case 'mo' :
                return $this->getOrdinalDaysOfMonth($startDate,$calendarPage,$pattern);
                break;
            case 'yd' :
                return $this->getDayOfYear($startDate,$calendarPage,$pattern);
                break;
            case 'yo' :
                return $this->getOrdinalDayOfYear($startDate,$calendarPage,$pattern);
                break;
            default:
                return false;
        }
    }

    private function getDaysSinceStart($startDate,$calendarPage, $pattern)
    {
        $rangeStart =  ($startDate >= $calendarPage->start) ? $startDate : $calendarPage->start;
        $options = new \stdClass();
        if ($pattern > 1) {
            $options->skip = $pattern;
            if ($startDate < $calendarPage->start) {
                $patternStartDate = new \DateTime($startDate);
                $rangeStartDate = new DateTime($calendarPage->start);
                $offset = ($patternStartDate->diff($rangeStartDate)->days % $pattern) - 1;
                $rangeStartDate->modify("+ $offset days");
                $rangeStart = $rangeStartDate->format('Y-m-d');
            }
        }
        return TDates::getDatesInRange($rangeStart,$calendarPage->end,$options);
    }

    private function getWeekDays($startDate,$calendarPage, $pattern)
    {
        $rangeStart =  ($startDate > $calendarPage->start) ? $startDate : $calendarPage->start;
        $options = new \stdClass();
        $options->weekDays = true;
        return TDates::getDatesInRange($rangeStart,$calendarPage->end,$options);
    }

    private function getWeekly($startDate,$calendarPage, $pattern)
    {
        @list($interval, $days) = explode(',', $pattern);
        $result = [];
        $startDate = new DateTime($startDate);
        $currentWeek = new DateTime($calendarPage->start);
        $endDate = new DateTime($calendarPage->end);
        if ($interval > 1 && $startDate < $currentWeek) {
            $firstDate = clone $startDate;
            if ($firstDate->format('D') !== 'Sun') {
                $firstDate->modify('sunday last week');
            }
            $weeks = $interval * 7;
            $offset = ($firstDate->diff($currentWeek)->days % $weeks);
            $currentWeek->modify("+ $offset days");
        }
        if ($startDate > $currentWeek) {
            $result[] = $startDate->format('Y-m-d');
        }
        $count = strlen($days);
        $nextWeek = sprintf("+ %d days",$interval*7);
        while ($currentWeek <= $endDate ) {
            for ($i=1;$i<=$count;$i++) {
                $dow = $days{$i-1};
                $date = TDates::GetDowThisWeek($currentWeek, $dow);
                if ($date >= $endDate) {
                    return $result;
                }
                if ($date > $startDate) {
                    $result[] = $date->format('Y-m-d');
                }
            }
            $currentWeek->modify($nextWeek);
        }

        return $result;

    }

    private function showThisMonth($monthDate,$interval,$startDate,$calendarStart) {
        if ($monthDate < $calendarStart) {
            return false;
        }

        if ($interval > 1) {
            $monthStart = clone $monthDate;

            if ($startDate->diff($calendarStart)->m % $interval !== 0) {
                return false;
            }
        }
        return true;
    }
    private function monthDates($month,$year,$startDate,$calendarPage,$pattern)
    {
        @list($day, $interval) = explode(',', $pattern);
        $startDate = new DateTime($startDate);
        $currentMonth = TDates::CreateDateTime(sprintf("%d-%d-%d",$year,$month,$day));
        $monthDate = new DateTime(sprintf("%d-%d-01",$year,$month));
        $monthDate->modify("-1 month");
        $prevMonth = TDates::CreateDateTime(sprintf("%d-%d-%d",$monthDate->format('Y'),$monthDate->format('m'),$day));
        $monthDate->modify("+2 month");
        $nextMonth = TDates::CreateDateTime(sprintf("%d-%d-%d",$monthDate->format('Y'),$monthDate->format('m'),$day));
        $calendarStart = new DateTime($calendarPage->start);
        $calendarEnd = new DateTime($calendarPage->end);
        $months = [];
        if ($prevMonth !== false) {
            $months[] = $prevMonth;
        }
        if ($currentMonth !== false) {
            $months[] = $currentMonth;
        }
        if ($nextMonth !== false) {
            $months[] = $nextMonth;
        }
        if (empty($months)) {
            return [];
        }
        $result = [];
        foreach ($months as $month) {
            if ($month >= $startDate && $month >= $calendarStart && $month <= $calendarEnd) {
                if ($interval > 1) {
                    $monthStart = new DateTime($month->format("Y-m-1"));
                    if ($startDate->diff($monthStart)->m % $interval !== 0) {
                        continue;
                    }
                }
                $result[] = $month->format('Y-m-d');
            }
        }
        return $result;
    }

    private function getOrdinalDaysOfMonth($startDate,$calendarPage,$pattern)
    {
        // todo: getOrdinalDaysOfMonth
        return [];
    }

    private function getDayOfYear($startDate,$calendarPage,$pattern)
    {
        //todo: getDayOfYear
        return [];
    }

    private function getOrdinalDayOfYear($startDate,$calendarPage,$pattern)
    {
        // todo: getOrdinalDayOfYear
        return [];
    }

    private function calculateEndDate($startDate, $days, $pattern)
    {
        // todo: support calculated end date
        return null;
    }

}