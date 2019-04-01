<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 1/22/2018
 * Time: 9:24 AM
 */

use Tops\sys\TDateRepeater;
use PHPUnit\Framework\TestCase;


class TDateRepeaterTest extends TestCase
{

    private function getRepeatSpec($repeatSpec) {
        @list($pattern, $range) = explode(';', $repeatSpec);
        if (empty($range)) {
            return 'error';
        }
        @list($startDate, $endDate) = explode(',', $range);
        $repeater = new TDateRepeater();
        $dates = $repeater->getRepeatDateRange($pattern,$startDate,$endDate);
        $pattern .= ';'.$dates[0];
        if (!empty($dates[1])) {
            $pattern .= ','.$dates[1];
        }
        return $pattern;
    }

    private function adjustStartDate($pattern,$startDate) {
        $repeater = new TDateRepeater();
        $dates = $repeater->getRepeatDateRange($pattern,$startDate,null);
        return $dates[0];
    }

    public function testDayPattern()
    {
        $month = 1;
        $year = 2018;


        $repeat = $this->getRepeatSpec('dd1;2017-12-28');
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $expected = 35;
        $this->assertNotEmpty($actual);
        $this->assertEquals('2017-12-31',$actual[0]);
        $this->assertEquals(sizeof($actual),35);

        $repeat = $this->getRepeatSpec('dd1;2017-12-28,2018-01-10');
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertNotEmpty($actual);
        $this->assertEquals('2017-12-31',$actual[0]);
        $this->assertEquals(sizeof($actual),10);

        $repeat = $this->getRepeatSpec('dd5;2017-12-28');
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $expected = [
            '2018-01-02',
            '2018-01-07',
            '2018-01-12',
            '2018-01-17',
            '2018-01-22',
            '2018-01-27',
            '2018-02-01',
        ];
        $this->assertEquals($expected,$actual);

    }

    public function testWeekdayPattern() {
        $month = 1;
        $year = 2018;

        $repeat = $this->getRepeatSpec('dw;2018-01-04,2018-01-31');
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertNotEmpty($actual);
        $this->assertEquals('2018-01-04',$actual[0]);
        $this->assertEquals(sizeof($actual),19);

        $repeat = $this->getRepeatSpec('dw;2017-01-26');
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertNotEmpty($actual);
        $this->assertEquals('2018-01-01',$actual[0]);
        $this->assertEquals(sizeof($actual),25);


    }

    public function testWeeklyPattern() {

        $month = 1;  $year = 2018;
        $repeat = $this->getRepeatSpec('wk2,34;2017-12-26');
        $expected = [
            '2018-01-09',
            '2018-01-10',
            '2018-01-23',
            '2018-01-24',
        ];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertEquals($expected,$actual);

        $month = 1;  $year = 2018;
        $repeat = $this->getRepeatSpec('wk2,34;2018-01-10,2018-01-24');
        $expected = [
            // '2018-01-09',
            '2018-01-10',
            '2018-01-23',
            // '2018-01-24',
        ];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertNotEmpty($actual);
        $this->assertEquals($expected,$actual);

        $month = 1;  $year = 2018;
        $repeat = $this->getRepeatSpec('wk1,34;2018-01-03');
        $expected = [
            '2018-01-03',
            '2018-01-09',
            '2018-01-10',
            '2018-01-16',
            '2018-01-17',
            '2018-01-23',
            '2018-01-24',
            '2018-01-30',
            '2018-01-31',
        ];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertNotEmpty($actual);
        $this->assertEquals($expected,$actual);

        $month = 1;  $year = 2018;
        $repeat = $this->getRepeatSpec('wk1,34;2017-12-24');
        $expected = [
            '2018-01-02',
            '2018-01-03',
            '2018-01-09',
            '2018-01-10',
            '2018-01-16',
            '2018-01-17',
            '2018-01-23',
            '2018-01-24',
            '2018-01-30',
            '2018-01-31',
        ];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertNotEmpty($actual);
        $this->assertEquals($expected,$actual);
    }

    public function testGetMontlyDatePattern() {
        $year = 2018;

        $month = 1;

        $repeat = $this->getRepeatSpec('md1,15;2018-01-18');
        $expected = [
            // '2018-01-15'
        ];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        // $this->assertNotEmpty($actual);
        $this->assertEquals($expected,$actual);

        $repeat = $this->getRepeatSpec('md1,15;2018-01-10');
        $expected = [
            '2018-01-15'
        ];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        // $this->assertNotEmpty($actual);
        $this->assertEquals($expected,$actual);

        $repeat = $this->getRepeatSpec('md1,31;2017-12-18');
        $expected = [
            '2017-12-31',
            '2018-01-31'
        ];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertNotEmpty($actual);
        $this->assertEquals($expected,$actual);

        $repeat = $this->getRepeatSpec('md1,23;2017-12-26');
        $expected = [
            '2018-01-23'
        ];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertNotEmpty($actual);
        $this->assertEquals($expected,$actual);


    }

    public function testThreeMonthIntervals() {
        $year = 2018;
        $month = 1;

        $expected = [
            '2017-07-07' => 1,
            '2017-08-07' => 0,
            '2017-09-07' => 0,
            '2017-10-07' => 1,
            '2017-11-07' => 0,
            '2017-12-07' => 0,
            '2018-01-07' => 1,
        ];
        $actual = [];
        foreach ($expected as $date => $count) {
            $repeat = $this->getRepeatSpec( 'md3,23;'.$date);
            $repeater = new TDateRepeater();
            $result = $repeater->getDates($year,$month,$repeat);
            $actual[$date] = sizeof($result);
        }
        $this->assertEquals($expected,$actual);

    }

    public function testOrdinalDowMonthsMultiple()
    {
        $month = 3; $year = 2019;
        $repeat = $this->getRepeatSpec('mo1,13,4;2019-03-01,2019-04-01');
        $expected = ['2019-03-06','2019-03-20']; // no april overlap
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertEquals($expected,$actual);

        $month = 3; $year = 2019;
        $repeat = $this->getRepeatSpec('mo1,24,4;2019-03-01,2019-04-01');
        $expected = ['2019-03-13','2019-03-27'];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertEquals($expected,$actual);

        $month = 2; $year = 2019;
        $repeat = $this->getRepeatSpec('mo1,24,4;2019-02-01');
        $expected = ['2019-02-13','2019-02-27'];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertEquals($expected,$actual);

        $month = 3; $year = 2019;
        $repeat = $this->getRepeatSpec('mo1,24,4;2019-02-01');
        $expected = ['2019-02-27','2019-03-13','2019-03-27']; // gets february calendar overlap
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertEquals($expected,$actual);

        $month = 3; $year = 2019;
        $repeat = $this->getRepeatSpec('mo1,24,4;2019-03-01');
        $expected = ['2019-03-13','2019-03-27']; // no overlap
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertEquals($expected,$actual);

        $month = 3; $year = 2019;
        $repeat = $this->getRepeatSpec('mo1,13,4;2019-03-01');
        $expected = ['2019-03-06','2019-03-20','2019-04-03']; // april overlap
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertEquals($expected,$actual);

        $month = 2; $year = 2019;
        $repeat = $this->getRepeatSpec('mo1,245,4;2019-02-01');
        $expected = ['2019-02-13','2019-02-27'];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertEquals($expected,$actual);

        $month = 2; $year = 2019;
        $repeat = $this->getRepeatSpec('mo1,134,4;2019-02-01');
        $expected = ['2019-02-06','2019-02-20','2019-02-27'];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertEquals($expected,$actual);
    }

    public function testOrdinalDowMonths() {
        // mo(interval),(ordinal),(day of week)

        $month = 1; $year = 2018;
        $repeat = $this->getRepeatSpec('mo1,5,1;2017-12-01');  // fifth sunday every month
        $expected = ['2017-12-31'];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertEquals($expected,$actual);


        $year = 2018;
        $month = 2;
        $repeat = $this->getRepeatSpec('mo1,6,1;2017-12-01');  // last sunday every month
        $expected = ['2018-01-28','2018-02-25'];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertEquals($expected,$actual);

        $month = 1;



        $month = 1;
        $repeat = $this->getRepeatSpec('mo1,4,7;2017-12-01');  // third saturday every month start before calendar
        $expected = ['2018-01-27'];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertEquals($expected,$actual);


        $repeat = $this->getRepeatSpec('mo2,3,7;2017-12-01');  // third saturday every other month start before first occurance
        $expected = [];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertEquals($expected,$actual);


        $repeat = $this->getRepeatSpec('mo3,3,7;2017-12-01');  // third saturday every three months start before calendar
        $expected = [];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertEquals($expected,$actual);

        $month = 1; $year = 2018;
        $repeat = $this->getRepeatSpec('mo3,3,7;2017-10-01');  // third saturday every three months start october
        $expected = ['2018-01-20'];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertEquals($expected,$actual);

        $repeat = $this->getRepeatSpec('mo3,3,7;2017-11-01');  // third saturday every three months start november
        $expected = [];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertEquals($expected,$actual);

        $repeat = $this->getRepeatSpec('mo2,2,3;2017-12-30');  // second tuesday every other month,
        $expected = [];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertEquals($expected,$actual);

        $repeat = $this->getRepeatSpec('mo2,2,3;2017-11-30');  // second tuesday every other month,
        $expected = ['2018-01-09'];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertEquals($expected,$actual);

        $repeat = $this->getRepeatSpec('mo2,2,3;2018-01-30');  // second tuesday every other month. Start too late for Jan,
        $expected = [];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertEquals($expected,$actual);


        $repeat = $this->getRepeatSpec('mo1,6,1;2017-12-01');  // last sunday every month
        $expected = ['2017-12-31','2018-01-28'];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertEquals($expected,$actual);



    }

    public function testRepeatingYears() {

        $year = 2018;
        $month = 1;
        $repeat = $this->getRepeatSpec('yd1,1,27;2017-01-01');  // Every year, jan 27
        $expected = ['2018-01-27'];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertEquals($expected,$actual);

        $year = 2018;
        $month = 1;
        $repeat = $this->getRepeatSpec('yd1,12,30;2017-01-01');  // Every year, dec 30
        $expected = [];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertEquals($expected,$actual);

        $year = 2018;
        $month = 1;
        $repeat = $this->getRepeatSpec('yd1,3,30;2017-01-01');  // Every year, march 30 (out of calendar
        $expected = [];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertEquals($expected,$actual);

        $year = 2018;
        $month = 1;
        $repeat = $this->getRepeatSpec('yd2,1,15;2017-01-01');
        // $expected = ['2018-01-15'];
        $expected = [];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertEquals($expected,$actual);

        $year = 2018;
        $month = 1;
        $repeat = $this->getRepeatSpec('yd2,1,15;2016-01-01');
        $expected = ['2018-01-15'];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertEquals($expected,$actual);

        $year = 2018;
        $month = 1;
        $repeat = $this->getRepeatSpec('yd3,1,15;2015-01-01');
        $expected = ['2018-01-15'];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertEquals($expected,$actual);

        $year = 2018;
        $month = 1;
        $repeat = $this->getRepeatSpec('yd3,1,15;2018-01-01');
        $expected = ['2018-01-15'];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertEquals($expected,$actual);

        $year = 2018;
        $month = 1;
        $repeat = $this->getRepeatSpec('yd3,1,15;2015-01-01');
        $expected = ['2018-01-15'];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertEquals($expected,$actual);


        for ($i = 16; $i<18; $i++) {
            $year = 2018;
            $month = 1;
            $repeat = $this->getRepeatSpec('yd3,1,15;20'.$i.'-01-01');
            $expected = [];
            $repeater = new TDateRepeater();
            $actual = $repeater->getDates($year,$month,$repeat);
            $this->assertEquals($expected,$actual);
        }


    }

    public function testOrdinalDayOfYear() {

        $month=1; $year=2018;
        $repeat = $this->getRepeatSpec('yo1,3,7,1;2016-01-01');
        $expected = [
            '2018-01-20'
        ];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertEquals($expected,$actual);

        $month=1; $year=2018;
        $repeat = $this->getRepeatSpec('yo1,1,6,2;2016-01-01');
        $expected = [
            '2018-02-02'
        ];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertEquals($expected,$actual);

        $month=1; $year=2018;
        $repeat = $this->getRepeatSpec('yo1,1,6,3;2016-01-01');
        $expected = [];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertEquals($expected,$actual);

        $month=1; $year=2018;
        $repeat = $this->getRepeatSpec('yo2,3,7,1;2017-01-01');
        $expected = [];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertEquals($expected,$actual);

        $month=1; $year=2018;
        $repeat = $this->getRepeatSpec('yo2,3,7,1;2016-01-01');
        $expected = [
            '2018-01-20'
        ];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertEquals($expected,$actual);
    }

    public function testCalculatedEndDatesWeekdays()
    {
        $occurances = 10;
        $interval = 1;
        $startDate = '2018-01-09';
        $expected = '2018-01-23';
        $pattern = 'dw'.$interval;
        $repeater = new TDateRepeater();
        $actual = $repeater->getRepeatDateRange($pattern,$startDate,$occurances);
        $this->assertEquals(2,sizeof($actual));
        $actual = $actual[1];
        $this->assertEquals($expected,$actual);


    }

    public function testCalculatedEndDatesDaily() {

        $occurances = 10;
        $interval = 1;
        $startDate = '2018-01-09';
        $expected = '2018-01-19';
        $pattern = 'dd'.$interval;
        $repeater = new TDateRepeater();
        $actual = $repeater->getRepeatDateRange($pattern,$startDate,$occurances);
        $this->assertEquals(2,sizeof($actual));
        $actual = $actual[1];
        $this->assertEquals($expected,$actual);


        $occurances = 10;
        $interval = 3;
        $startDate = '2018-01-09';
        $expected = '2018-02-06';
        $pattern = 'dd'.$interval;
        $repeater = new TDateRepeater();
        $actual = $repeater->getRepeatDateRange($pattern,$startDate,$occurances);
        $this->assertEquals(2,sizeof($actual));
        $actual = $actual[1];
        $this->assertEquals($expected,$actual);
    }

    public function testCalculatedEndDatesWeeklyDows() {
        $days = '135';
        $occurances = 8;
        $interval = 2;
        $startDate = '2018-01-09';
        $expected = '2018-02-09';
        $pattern = 'wk'.$interval.','.$days;
        $repeater = new TDateRepeater();
        $actual = $repeater->getRepeatDateRange($pattern,$this->adjustStartDate($pattern,$startDate),$occurances);
        $this->assertEquals(2,sizeof($actual));
        $actual = $actual[1];
        $this->assertEquals($expected,$actual);

        $days = '135';
        $occurances = 8;
        $interval = 2;
        $startDate = '2018-01-21';
        $expected = '2018-02-21';
        $pattern = 'wk'.$interval.','.$days;
        $actual = $repeater->getRepeatDateRange($pattern,$this->adjustStartDate($pattern,$startDate),$occurances);
        $this->assertEquals(2,sizeof($actual));
        $actual = $actual[1];
        $this->assertEquals($expected,$actual);

        $days = '135';
        $occurances = 9;
        $interval = 2;
        $startDate = '2018-01-21';
        $expected = '2018-02-23';
        $pattern = 'wk'.$interval.','.$days;
        $actual = $repeater->getRepeatDateRange($pattern,$this->adjustStartDate($pattern,$startDate),$occurances);
        $this->assertEquals(2,sizeof($actual));
        $actual = $actual[1];
        $this->assertEquals($expected,$actual);

        $days = '135';
        $occurances = 8;
        $interval = 1;
        $startDate = '2018-01-14';
        $expected = '2018-01-31';
        $pattern = 'wk'.$interval.','.$days;
        $actual = $repeater->getRepeatDateRange($pattern,$this->adjustStartDate($pattern,$startDate),$occurances);
        $this->assertEquals(2,sizeof($actual));
        $actual = $actual[1];
        $this->assertEquals($expected,$actual);

        $days = '135';
        $occurances = 8;
        $interval = 1;
        $startDate = '2018-01-09';
        $expected = '2018-01-26';
        $pattern = 'wk'.$interval.','.$days;
        $actual = $repeater->getRepeatDateRange($pattern,$this->adjustStartDate($pattern,$startDate),$occurances);
        $this->assertEquals(2,sizeof($actual));
        $actual = $actual[1];
        $this->assertEquals($expected,$actual);

        $interval = 1; // for all remaining

        $days = '235';
        $occurances = 8;
        $startDate = '2018-01-11';
        $expected = '2018-01-30';
        $pattern = 'wk'.$interval.','.$days;
        $actual = $repeater->getRepeatDateRange($pattern,$this->adjustStartDate($pattern,$startDate),$occurances);
        $this->assertEquals(2,sizeof($actual));
        $actual = $actual[1];
        $this->assertEquals($expected,$actual);


        $days = '235';
        $occurances = 9;
        $startDate = '2018-01-11';
        $expected = '2018-01-31';
        $pattern = 'wk'.$interval.','.$days;
        $actual = $repeater->getRepeatDateRange($pattern,$this->adjustStartDate($pattern,$startDate),$occurances);
        $this->assertEquals(2,sizeof($actual));
        $actual = $actual[1];
        $this->assertEquals($expected,$actual);

        $days = '235';
        $occurances = 9;
        $startDate = '2018-01-08';
        $expected = '2018-01-26';
        $pattern = 'wk'.$interval.','.$days;
        $actual = $repeater->getRepeatDateRange($pattern,$this->adjustStartDate($pattern,$startDate),$occurances);
        $this->assertEquals(2,sizeof($actual));
        $actual = $actual[1];
        $this->assertEquals($expected,$actual);


        $days = '35';
        $occurances = 6;
        $startDate = '2018-01-09';
        $expected = '2018-01-26';
        $pattern = 'wk'.$interval.','.$days;
        $actual = $repeater->getRepeatDateRange($pattern,$this->adjustStartDate($pattern,$startDate),$occurances);
        $this->assertEquals(2,sizeof($actual));
        $actual = $actual[1];
        $this->assertEquals($expected,$actual);

        $days = '35';
        $occurances = 6;
        $startDate = '2018-01-11';
        $expected = '2018-01-31';
        $pattern = 'wk'.$interval.','.$days;
        $actual = $repeater->getRepeatDateRange($pattern,$this->adjustStartDate($pattern,$startDate),$occurances);
        $this->assertEquals(2,sizeof($actual));
        $actual = $actual[1];
        $this->assertEquals($expected,$actual);

    }

    public function testCalculatedEndDatesMonthDate()
    {
        $day = 9;
        $occurances = 3;
        $interval = 1;
        $startDate = '2018-01-09';
        $expected = '2018-03-10';
        $pattern = 'md'.$interval.','.$day;
        $repeater = new TDateRepeater();
        $actual = $repeater->getRepeatDateRange($pattern,$startDate,$occurances);
        $this->assertEquals(2,sizeof($actual));
        $actual = $actual[1];
        $this->assertEquals($expected,$actual);

        $day = 9;
        $occurances = 3;
        $interval = 3;
        $startDate = '2018-01-09';
        $expected = '2018-07-10';
        $pattern = 'md'.$interval.','.$day;
        $repeater = new TDateRepeater();
        $actual = $repeater->getRepeatDateRange($pattern,$startDate,$occurances);
        $this->assertEquals(2,sizeof($actual));
        $actual = $actual[1];
        $this->assertEquals($expected,$actual);


    }

    public function testCalculatedEndDatesMonthDowMultiple()
    {
        $ordinal = '2';
        $dow = 3;
        $occurances = 3;
        $interval = 3;
        $startDate = '2018-01-09';
        $expected = '2018-07-11';

        $pattern = 'mo'.$interval.",$ordinal,$dow";
        $repeater = new TDateRepeater();
        $actual = $repeater->getRepeatDateRange($pattern,$startDate,$occurances);
        $this->assertEquals(2,sizeof($actual));
        $actual = $actual[1];
        $this->assertEquals($expected,$actual);


        $ordinal = '23';
        $dow = 3;
        $occurances = 3;
        $interval = 3;
        $startDate = '2018-01-09';
        $expected = '2018-07-18';
        $pattern = 'mo'.$interval.",$ordinal,$dow";
        $repeater = new TDateRepeater();
        $actual = $repeater->getRepeatDateRange($pattern,$startDate,$occurances);
        $this->assertEquals(2,sizeof($actual));
        $actual = $actual[1];
        $this->assertEquals($expected,$actual);
    }

    public function testCalculatedEndDatesMonthDow()
    {
        $ordinal = 2;
        $dow = 3;
        $occurances = 3;
        $interval = 1;
        $startDate = '2018-01-09';
        $expected = '2018-03-14';
        $pattern = 'mo'.$interval.",$ordinal,$dow";
        $repeater = new TDateRepeater();
        $actual = $repeater->getRepeatDateRange($pattern,$startDate,$occurances);
        $this->assertEquals(2,sizeof($actual));
        $actual = $actual[1];
        $this->assertEquals($expected,$actual);

        $ordinal = 2;
        $dow = 3;
        $occurances = 3;
        $interval = 3;
        $startDate = '2018-01-09';
        $expected = '2018-07-11';
        $pattern = 'mo'.$interval.",$ordinal,$dow";
        $repeater = new TDateRepeater();
        $actual = $repeater->getRepeatDateRange($pattern,$startDate,$occurances);
        $this->assertEquals(2,sizeof($actual));
        $actual = $actual[1];
        $this->assertEquals($expected,$actual);


    }

    public function testCalculatedEndDatesYearDate()
    {
        $month = 1;
        $day = 9;
        $occurances = 3;
        $interval = 1;
        $startDate = '2018-01-09';
        $expected = '2020-01-10';
        $pattern = 'yd'.$interval.",$month,$day";
        $repeater = new TDateRepeater();
        $actual = $repeater->getRepeatDateRange($pattern,$startDate,$occurances);
        $this->assertEquals(2,sizeof($actual));
        $actual = $actual[1];
        $this->assertEquals($expected,$actual);

        $month = 1;
        $day = 9;
        $occurances = 3;
        $interval = 3;
        $startDate = '2018-01-09';
        $expected = '2024-01-10';
        $pattern = 'yd'.$interval.",$month,$day";
        $repeater = new TDateRepeater();
        $actual = $repeater->getRepeatDateRange($pattern,$startDate,$occurances);
        $this->assertEquals(2,sizeof($actual));
        $actual = $actual[1];
        $this->assertEquals($expected,$actual);

    }

    public function testCalculatedEndDatesYearOrdinal()
    {
        $ordinal = 2;
        $month = 1;
        $dow = 3;
        $occurances = 3;
        $interval = 1;
        $startDate = '2018-01-09';
        $expected = '2020-01-15';
        $pattern = 'yo'.$interval.",$ordinal,$dow,$month";
        $repeater = new TDateRepeater();
        $actual = $repeater->getRepeatDateRange($pattern,$startDate,$occurances);
        $this->assertEquals(2,sizeof($actual));
        $actual = $actual[1];
        $this->assertEquals($expected,$actual);

        $ordinal = 2;
        $month = 1;
        $dow = 3;
        $occurances = 3;
        $interval = 2;
        $startDate = '2018-01-09';
        $expected = '2022-01-12';
        $pattern = 'yo'.$interval.",$ordinal,$dow,$month";
        $repeater = new TDateRepeater();
        $actual = $repeater->getRepeatDateRange($pattern,$startDate,$occurances);
        $this->assertEquals(2,sizeof($actual));
        $actual = $actual[1];
        $this->assertEquals($expected,$actual);

    }


}
