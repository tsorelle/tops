<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 11/1/2017
 * Time: 5:37 AM
 */

use Tops\sys\TDates;
use PHPUnit\Framework\TestCase;

class TDatesTest extends TestCase
{
    public function testGetValidDate() {
        $date = '9/12/47';
        $expected = false;
        $actual = TDates::getValidDate($date);
        $this->assertEquals($expected,$actual,"Input: $date");

        $date = 'bad date';
        $expected = false;
        $actual = TDates::getValidDate($date);
        $this->assertEquals($expected,$actual,"Input: $date");

        $date = '9/12/1947';
        $expected = '1947-12-09';
        $actual = TDates::getValidDate($date);
        $this->assertEquals($expected,$actual,"Input: $date");

        $date = '1947-12-09';
        $expected = '1947-12-09';
        $actual = TDates::getValidDate($date);
        $this->assertEquals($expected,$actual,"Input: $date");


        $date = '9/2/1947';
        $expected = '09/02/1947';
        $actual = TDates::getValidDate($date,TDates::returnTypeCorrectedDate);
        $this->assertEquals($expected,$actual,"Input: $date, corrected date return");


        $date = '9/12/1947';
        $expected = '1947-09-12';
        $actual = TDates::getValidDate($date,TDates::returnTypeDateObject);
        $this->assertInstanceOf('\DateTime',$actual);
        $actual = $actual->format(TDates::MySqlDateFormat);
        $this->assertEquals($expected,$actual,"Input: $date, date object return");

        $date = '9/12/1947';
        $expected = '1947-09-12';
        $actual = TDates::getValidDate($date,TDates::returnTypeTimeStamp);
        $actual = date(TDates::MySqlDateFormat,$actual);
        $this->assertEquals($expected,$actual,"Input: $date, timestamp return");

    }

    public function testStringToInterval() {
        $s = '10 ooblkekx';
        $actual = TDates::StringToInterval($s);
        $this->assertTrue($actual === false);

        $s = 'dont work';
        $actual = TDates::StringToInterval($s);
        $this->assertTrue($actual === false);


        $s = '7 days';
        $expected = 7;
        $actual = TDates::StringToInterval($s);
        $this->assertTrue($actual !== false,$actual);
        $this->assertEquals(0,$actual->y);
        $this->assertEquals(0,$actual->m);
        $this->assertEquals($expected,$actual->d);
        $this->assertEquals(0,$actual->h);
        $this->assertEquals(0,$actual->i);
        $this->assertEquals(0,$actual->s);
        $this->assertEquals(0,$actual->invert);
        $sevendays = $actual;

        $s = '30 minutes';
        $expected = 30;
        $actual = TDates::StringToInterval($s);
        $this->assertTrue($actual !== false,$actual);
        $this->assertEquals(0,$actual->y);
        $this->assertEquals(0,$actual->m);
        $this->assertEquals(0,$actual->d);
        $this->assertEquals(0,$actual->h);
        $this->assertEquals($expected,$actual->i);
        $this->assertEquals(0,$actual->s);
        $this->assertEquals(0,$actual->invert);
        $thirtyminutes = $actual;

        $s = '30 seconds';
        $expected = 30;
        $actual = TDates::StringToInterval($s);
        $this->assertTrue($actual !== false,$actual);
        $this->assertEquals(0,$actual->y);
        $this->assertEquals(0,$actual->m);
        $this->assertEquals(0,$actual->d);
        $this->assertEquals(0,$actual->h);
        $this->assertEquals(0,$actual->i);
        $this->assertEquals($expected,$actual->s);
        $this->assertEquals(0,$actual->invert);

        $s = '6 months';
        $expected = 6;
        $actual = TDates::StringToInterval($s);
        $this->assertTrue($actual !== false,$actual);
        $this->assertEquals(0,$actual->y);
        $this->assertEquals($expected,$actual->m);
        $this->assertEquals(0,$actual->d);
        $this->assertEquals(0,$actual->h);
        $this->assertEquals(0,$actual->i);
        $this->assertEquals(0,$actual->s);
        $this->assertEquals(0,$actual->invert);
        $sixmonths = $actual;

        $s = '30 seconds';
        $expected = 30;
        $actual = TDates::StringToInterval($s);
        $this->assertTrue($actual !== false,$actual);
        $this->assertEquals(0,$actual->y);
        $this->assertEquals(0,$actual->m);
        $this->assertEquals(0,$actual->d);
        $this->assertEquals(0,$actual->h);
        $this->assertEquals(0,$actual->i);
        $this->assertEquals($expected,$actual->s);
        $this->assertEquals(0,$actual->invert);
        $thirtyseconds = $actual;

        $s = '1 hour';
        $expected = 1;
        $actual = TDates::StringToInterval($s);
        $this->assertTrue($actual !== false,$actual);
        $this->assertEquals(0,$actual->y);
        $this->assertEquals(0,$actual->m);
        $this->assertEquals(0,$actual->d);
        $this->assertEquals(0,$actual->i);
        $this->assertEquals(0,$actual->s);
        $this->assertEquals($expected,$actual->h);
        $this->assertEquals(0,$actual->invert);
        $onehour = $actual;




        $date = new \DateTime('2017-12-28');
        $expected = '2018-01-04';
        $date = $date->add($sevendays);
        $actual = $date->format(TDates::MySqlDateFormat);
        $this->assertEquals($expected,$actual);

        $date = new \DateTime('2017-11-28');
        $expected = '2018-05-28';
        $date = $date->add($sixmonths);
        $actual = $date->format(TDates::MySqlDateFormat);
        $this->assertEquals($expected,$actual);


        $testdate = '2017-11-01 09:15:00';
        $expected = '2017-11-01 09:15:30';
        $date =  new \DateTime($testdate);
        $actual = $date->add($thirtyseconds)->format('Y-m-d H:i:s');
        $this->assertEquals($expected,$actual);

        $testdate = '2017-11-01 09:15:31';
        $expected = '2017-11-01 09:16:01';
        $date =  new \DateTime($testdate);
        $actual = $date->add($thirtyseconds)->format('Y-m-d H:i:s');
        $this->assertEquals($expected,$actual);

        $testdate = '2017-11-01 09:45:31';
        $expected = '2017-11-01 10:15:31';
        $date =  new \DateTime($testdate);
        $actual = $date->add($thirtyminutes)->format('Y-m-d H:i:s');
        $this->assertEquals($expected,$actual);

        $testdate = '2017-11-01 23:59:31';
        $expected = '2017-11-02 00:00:01';
        $date =  new \DateTime($testdate);
        $actual = $date->add($thirtyseconds)->format('Y-m-d H:i:s');
        $this->assertEquals($expected,$actual);


    }

    public function testCompareDates() {
        $format = 'Y-m-d H:i:s';
        $today = new \DateTime();
        $todayString = $today->format($format);

        // equal
        $left = $todayString;
        $incremented = (clone $today)->add(new DateInterval('P7D'))->format($format);
        $right = $incremented;
        $expected = TDates::Equal;
        $actual = TDates::CompareDates($left,'7 days',$right);
        $this->assertEquals($expected,$actual,'1: left should equal right');

        // left after right
        $base = (clone $today)->add(new DateInterval('P3D'));
        $left = $base->format($format);
        $incremented = (clone $base)->add(new DateInterval('P7D'))->format($format);
        $right = $todayString;
        $expected =  TDates::After;
        $actual = TDates::CompareDates($left,'7 days',$right);
        // print "2: $incremented after $right\n";
        $this->assertEquals($expected,$actual,"2: $incremented after $right");

        // left before right
        $base = (clone $today)->sub(new DateInterval('P15D'));
        $left = $base->format($format);
        $incremented = (clone $base)->add(new DateInterval('P7D'))->format($format);
        $right = $todayString;
        $expected = TDates::Before;
        $actual = TDates::CompareDates($left,'7 days',$right);
        // print "3: $incremented before $right\n";
        $this->assertEquals($expected,$actual,"3: $incremented before $right");
    }

}
