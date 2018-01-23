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
        $format = TDates::MySqlDateTimeFormat; // 'Y-m-d H:i:s';
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

    public function testCompareNow() {
        $test = new \DateTime();
        $i = TDates::StringToInterval('1 hour');
        $test->add(TDates::StringToInterval('1 hour'));
        $actual = TDates::CompareWithNow($test->format(TDates::MySqlDateTimeFormat));
        $this->assertEquals(TDates::After,$actual);

        $test = new \DateTime();
        $test->sub(TDates::StringToInterval('1 hour'));
        $actual = TDates::CompareWithNow($test->format(TDates::MySqlDateTimeFormat));
        $this->assertEquals(TDates::Before,$actual);
    }

    private function getDtoDate($object,$propertyName) {
        if (property_exists($object,$propertyName)) {
            $result = TDates::formatMySqlDate($object->$propertyName);
            return $result;

        }
        return false;
    }

    public function testFormatMySqlDate() {
        $dt = '';
        $actual = TDates::formatMySqlDate($dt);
        $this->assertTrue($actual===null);
        $dt = null;
        $actual = TDates::formatMySqlDate($dt);
        $this->assertTrue($actual===null);
        $dt = '0000-00-00';
        $actual = TDates::formatMySqlDate($dt);
        $this->assertTrue($actual===null);
        $dt = '9/12/1947';
        $expected = '1947-09-12';
        $actual = TDates::formatMySqlDate($dt);
        $this->assertEquals($expected,$actual);

        $dt = 'invalid date';
        $actual = TDates::formatMySqlDate($dt);
        $this->assertTrue($actual===false);

        $dt = '0000-00-00 00:00:00';
        $actual = TDates::formatMySqlDate($dt,true);
        $this->assertTrue($actual===null);

        $dt = '9/12/1947 1:23 pm';
        $expected = '1947-09-12 13:23:00';
        $actual = TDates::formatMySqlDate($dt,true);
        $this->assertEquals($expected,$actual);

        $dt = 'invalid date';
        $actual = TDates::formatMySqlDate($dt,true);
        $this->assertTrue($actual===false);
    }

    public function testObjectDate() {
        $test = new stdClass();
        $actual = TDates::GetMySqlDateProperty($test,'dt');
        $this->assertTrue($actual===false);

        $test->dt = 'invalid date';
        $actual = TDates::GetMySqlDateProperty($test,'dt');
        $this->assertTrue($actual===false);

        $test->dt = null;
        $actual = TDates::GetMySqlDateProperty($test,'dt');
        $this->assertTrue($actual===null);

        $test->dt = '0000-00-00';
        $actual = TDates::GetMySqlDateProperty($test,'dt');
        $this->assertTrue($actual===null);

        $test->dt = '0000-00-00 00:00:00';
        $actual = TDates::GetMySqlDateProperty($test,'dt');
        $this->assertTrue($actual===null);

        $test->dt = '';
        $actual = TDates::GetMySqlDateProperty($test,'dt');
        $this->assertTrue($actual===null);

        $test->dt = '9/12/1947';
        $expected = '1947-09-12';
        $actual = TDates::GetMySqlDateProperty($test,'dt');
        $this->assertEquals($expected,$actual);

        $test->dt = '9/12/1947 1:23 pm';
        $expected = '1947-09-12 13:23:00';
        $actual = TDates::GetMySqlDateProperty($test,'dt',true);
        $this->assertEquals($expected,$actual);

    }

    public function testGetWeekDates() {

        $expected = [
            1 => [
                '2017-07-02',
                '2017-07-09',
                '2017-07-16',
                '2017-07-23',
                '2017-07-30',
            ],
            2 => [
                '2017-07-03',
                '2017-07-10',
                '2017-07-17',
                '2017-07-24',
                '2017-07-31',
            ],
            3 => [
                '2017-07-04',
                '2017-07-11',
                '2017-07-18',
                '2017-07-25',
            ],
            4 => [
                '2017-07-05',
                '2017-07-12',
                '2017-07-19',
                '2017-07-26',
            ],
            5 => [
                '2017-07-06',
                '2017-07-13',
                '2017-07-20',
                '2017-07-27',
            ],
            6 => [
                '2017-07-07',
                '2017-07-14',
                '2017-07-21',
                '2017-07-28',
            ],
            7 => [
                '2017-07-01',
                '2017-07-08',
                '2017-07-15',
                '2017-07-22',
                '2017-07-29',
            ],

        ];
        $actual = [];
        for($i=1;$i<8;$i++) {
            $actual[$i] = TDates::getWeekDates(2017,7,$i);
        }

        self::assertEquals($expected,$actual);
    }


    public function testGetDatesInRange() {
        $actual = TDates::getDatesInRange('2018-1-11','2018-1-24');
        $this->assertNotEmpty($actual);

        $options = new stdClass();
        $options->weekDays = true;
        $actual = TDates::getDatesInRange('2018-1-11','2018-1-24',$options);
        $this->assertNotEmpty($actual);

        $options = new stdClass();
        $options->skip = 3;
        $actual = TDates::getDatesInRange('2018-1-11','2018-1-24',$options);
        $this->assertNotEmpty($actual);
    }

}
