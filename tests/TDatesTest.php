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
    /**
     * @throws Exception
     */
    public function testMonthIncrementNext() {
        $inDate = '2019-03-31';
        $date = new \DateTime($inDate);
        $expected = 4;
        TDates::IncrementMonth($date,1);
        $actual = $date->format('n');
        $this->assertEquals($expected,
            $actual,
            "Input: $inDate");
    }

    /**
     * @throws Exception
     */
    public function testMonthIncrementPrev() {
        $inDate = '2019-04-30';
        $date = new \DateTime($inDate);
        $expected = '2019-02-28';
        $actual = TDates::IncrementMonth($date,-2);
        $this->assertEquals($expected,
            $actual,
            "Input: $inDate");
    }

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
        $dt = '       ';
        $actual = TDates::formatMySqlDate($dt);
        $this->assertTrue($actual===null);

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
        $start = '2018-1-11';
        $end  = '2018-1-25';
        $expectedStart = date('Y-m-d',strtotime($start));
        $expectedEnd = (new DateTime($end))->modify('- 1 day')->format('Y-m-d');

        $len = 14;
        $actual = TDates::getDatesInRange(new DateTime($start),new DateTime($end));
        $this->assertNotEmpty($actual);
        $this->assertEquals($len,sizeof($actual));
        $this->assertEquals($expectedStart,$actual[0]);
        $this->assertEquals($expectedEnd,$actual[$len -1]);


        $options = new stdClass();
        $options->weekDays = true;
        $len = 10;
        $actual = TDates::getDatesInRange(new DateTime($start),new DateTime($end),$options);
        $this->assertNotEmpty($actual);
        $this->assertEquals($len,sizeof($actual));
        $this->assertEquals($expectedStart,$actual[0]);
        $this->assertEquals($expectedEnd,$actual[$len -1]);

        $options = new stdClass();
        $options->skip = 3;
        $len = 5;
        $actual = TDates::getDatesInRange(new DateTime($start),new DateTime($end),$options);
        $this->assertNotEmpty($actual);
        $this->assertEquals($len,sizeof($actual));
        $this->assertEquals($expectedStart,$actual[0]);
        $this->assertEquals('2018-01-23',$actual[$len -1]);

    }

    public function testSetDowThisWeek() {
        $currentDate = new DateTime('2017-12-31');
        $expectedDates = [
            '2017-12-31',
            '2018-01-01',
            '2018-01-02',
            '2018-01-03',
            '2018-01-04',
            '2018-01-05',
            '2018-01-06',
        ];
        for ($d = 0; $d<7; $d++) {
            for ($i = 0; $i < 7; $i++) {
                $day = TDates::DowNames[$i];
                $actual = clone $currentDate;
                TDates::SetDowThisWeek($actual, $day);
                $this->assertEquals($expectedDates[$i], $actual->format('Y-m-d'));

                $day = $i + 1;
                $actual = clone $currentDate;
                TDates::SetDowThisWeek($actual, $day);
                $this->assertEquals($expectedDates[$i], $actual->format('Y-m-d'));
            }
            $currentDate->modify("+ 1 day");
        }
    }

    public function testGetCalendarDates() {
        $actual = TDates::GetCalendarMonth(2018,1);
        $expectedStart=new DateTime('2017-12-31');
        $expectedEnd=new DateTime('2018-02-04');
        $this->assertEquals($expectedStart,$actual->start,'start');
        $this->assertEquals($expectedEnd,$actual->end,'end');

        $actual = TDates::GetCalendarMonth(2018,2,'right');
        $expectedStart=new DateTime('2018-02-04');
        $expectedEnd=new DateTime('2018-03-04');
        $this->assertEquals($expectedStart,$actual->start,'right-start');
        $this->assertEquals($expectedEnd,$actual->end,'right-end');

        $actual = TDates::GetCalendarMonth(2017,12,'left');
        $expectedStart=new DateTime('2017-11-26');
        $expectedEnd=new DateTime('2017-12-31');
        $this->assertEquals($expectedStart,$actual->start,'left-start');
        $this->assertEquals($expectedEnd,$actual->end,'left-end');




        /*
                $actual = TDates::GetCalendarMonth(2018,2);
                $expectedStart='2018-01-28';
                $expectedEnd='2018-03-04';
                $this->assertEquals($expectedStart,$actual->start);
                $this->assertEquals($expectedEnd,$actual->end);

                $actual = TDates::GetCalendarMonth(2018,3);
                $expectedStart='2018-02-25';
                $expectedEnd='2018-04-01';
                $this->assertEquals($expectedStart,$actual->start);
                $this->assertEquals($expectedEnd,$actual->end);

                $actual = TDates::GetCalendarMonth(2018,4);
                $expectedStart='2018-04-01';
                $expectedEnd='2018-05-06';
                $this->assertEquals($expectedStart,$actual->start);
                $this->assertEquals($expectedEnd,$actual->end);

                $actual = TDates::GetCalendarMonth(2018,5);
                $expectedStart='2018-04-29';
                $expectedEnd='2018-06-03';
                $this->assertEquals($expectedStart,$actual->start);
                $this->assertEquals($expectedEnd,$actual->end);

                $actual = TDates::GetCalendarMonth(2018,6);
                $expectedStart='2018-05-27';
                $expectedEnd='2018-07-01';
                $this->assertEquals($expectedStart,$actual->start);
                $this->assertEquals($expectedEnd,$actual->end);

                $actual = TDates::GetCalendarMonth(2018,7);
                $expectedStart='2018-07-01';
                $expectedEnd='2018-08-05';
                $this->assertEquals($expectedStart,$actual->start);
                $this->assertEquals($expectedEnd,$actual->end);

                $actual = TDates::GetCalendarMonth(2018,8);
                $expectedStart='2018-07-29';
                $expectedEnd='2018-09-02';
                $this->assertEquals($expectedStart,$actual->start);
                $this->assertEquals($expectedEnd,$actual->end);

                $actual = TDates::GetCalendarMonth(2018,9);
                $expectedStart='2018-08-26';
                $expectedEnd='2018-10-07';
                $this->assertEquals($expectedStart,$actual->start);
                $this->assertEquals($expectedEnd,$actual->end);

                $actual = TDates::GetCalendarMonth(2018,10);
                $expectedStart='2018-09-30';
                $expectedEnd='2018-11-04';
                $this->assertEquals($expectedStart,$actual->start);
                $this->assertEquals($expectedEnd,$actual->end);

                $actual = TDates::GetCalendarMonth(2018,11);
                $expectedStart='2018-10-28';
                $expectedEnd='2018-12-02';
                $this->assertEquals($expectedStart,$actual->start);
                $this->assertEquals($expectedEnd,$actual->end);

                $actual = TDates::GetCalendarMonth(2018,12);
                $expectedStart='2018-11-25';
                $expectedEnd='2019-01-06';
                $this->assertEquals($expectedStart,$actual->start);
                $this->assertEquals($expectedEnd,$actual->end);*/
    }

    public function testDowListToArray() {
        $days = '1234567';
        $expected = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
        $actual = TDates::DowListToArray($days);
        $this->assertEquals($expected,$actual);

        $days = '247';
        $expected = ['Mon','Wed','Sat'];
        $actual = TDates::DowListToArray($days);
        $this->assertEquals($expected,$actual);
    }

    public function testDowRoutines() {
        for ($expected = 1; $expected < 8;$expected++) {
            $dow = TDates::GetDowName($expected);
            $this->assertTrue(in_array($dow,TDates::DowNames));
            $actual =  TDates::GetDowNumber($dow);
            $this->assertEquals($expected,$actual);
        }
    }

    public function testCreateDateTime() {
        $expected = '2018-02-28';
        $test = '2018-02-30';
        $actual = TDates::CreateDateTime($test);
        $this->assertEquals($expected,$actual->format('Y-m-d'));

        $expected = '2018-09-30';
        $test = '2018-9-31';
        $actual = TDates::CreateDateTime($test);
        $this->assertEquals($expected,$actual->format('Y-m-d'));

        $test = '2018-9-31';
        $actual = TDates::CreateDateTime($test,TDates::ConstrainMonth); // don't fix invalid date
        $this->assertTrue($actual === false);

        $expected = '2018-02-27';
        $actual = TDates::CreateDateTime($expected);
        $this->assertEquals($expected,$actual->format('Y-m-d'));

        $expected = '2018-02-07';
        $test = '2018-2-7';
        $actual = TDates::CreateDateTime($test);
        $this->assertEquals($expected,$actual->format('Y-m-d'));

        $test= '2018-13-07';
        $actual = TDates::CreateDateTime($test);
        $this->assertTrue($actual === false);

        $test= 'invalid';
        $actual = TDates::CreateDateTime($test);
        $this->assertTrue($actual === false);
    }

    public function testLastMonthDay() {
        $year = 2018;
        $month='02';
        $expected = 28;
        $actual = TDates::GetLastDayOfMonth($year,$month);
        $this->assertEquals($expected,$actual);

        $month='foo';
        $expected = false;
        $actual = TDates::GetLastDayOfMonth($year,$month);
        $this->assertEquals($expected,$actual);

        $month=9;
        $expected = 30;
        $actual = TDates::GetLastDayOfMonth($year,$month);
        $this->assertEquals($expected,$actual);

        $month=1;
        $expected = 31;
        $actual = TDates::GetLastDayOfMonth($year,$month);
        $this->assertEquals($expected,$actual);

    }

    public function testModifyDate() {
        $year = 2018;
        $month= 1;
        $day = 1;
        $date = new DateTime("$year-$month-$day");
        $test = clone $date;
        $expected = '2018-01-10';
        $result = TDates::ModifyDate($date,'2 Wed');
        $this->assertTrue($result !== false);
        $this->assertEquals($result,$expected);

        $result = TDates::SetOrdinalDayOfMonth($test, 2, 4);
        $this->assertTrue($result !== false);
        $this->assertEquals($result,$expected);
        $this->assertEquals($test,$date);

        $month = 2;
        $date = new DateTime("$year-$month-$day");
        $test = clone $date;
        $result = TDates::SetOrdinalDayOfMonth($test, 5, 4);
        $this->assertTrue($result === false);
        $this->assertEquals($test,$date);

        $month= 1;
        $day = 1;
        $date = new DateTime("$year-$month-$day");
        $expected = '2018-01-10';
        $result = TDates::SetOrdinalDayOfMonth($date, 2, 'Wed');
        $this->assertTrue($result !== false);
        $this->assertEquals($expected,$result);


    }

    public function testIncrementDays() {
        $year = 2018;
        $month= 1;
        $day = 1;
        $date = new DateTime("$year-$month-$day");
        $test = clone $date;
        $expected = '2018-01-11';
        $result = TDates::IncrementDate($date,10,'days',TDates::ConstrainMonth);
        $this->assertTrue($result !== false);
        $this->assertEquals($expected,$result);

        $year = 2018;
        $month= 1;
        $day = 17;
        $date = new DateTime("$year-$month-$day");
        $test = clone $date;
        $expected = '2018-01-11';
        $result = TDates::IncrementDate($date,20,'days',TDates::ConstrainMonth);
        $this->assertTrue($result === false);
        $this->assertEquals($date,$test);
    }

    public function testSetDay() {
        $year = 2018;
        $month= 1;
        $day = 1;
        $date = new DateTime("$year-$month-$day");
        $test = clone $date;
        $expected = '2018-01-11';
        $result = TDates::SetDayOfMonth($date,11);
        $this->assertTrue($result !== false);
        $this->assertEquals($expected,$result);
        $this->assertEquals($expected, $date->format('Y-m-d'));

        $year = 2018;
        $month= 2;
        $day = 17;
        $date = new DateTime("$year-$month-$day");
        $test = clone $date;
        $result = TDates::SetDayOfMonth($date,31);
        $this->assertTrue($result === false);
        $this->assertEquals($date,$test);

    }

    public function testGetEndOfMonth() {
        $year = 2018;
        $month= 2;
        $day = 17;
        $test = new DateTime("$year-$month-$day");
        $expected = new DateTime("$year-$month-28");
        $actual = TDates::GetEndOfMonth($test);
        $this->assertEquals($expected,$actual);

    }

    public function testFirstWeekday() {
        $start = new \DateTime('2019-03-01');

        TDates::IncrementToDOW($start,4);
        $expected = '2019-03-06';
        $actual = $start->format('Y-m-d');
        $this->assertEquals($expected,$actual);

        $start = new \DateTime('2019-03-01');
        TDates::IncrementToDOW($start,4,2);
        $expected = '2019-03-13';
        $actual = $start->format('Y-m-d');
        $this->assertEquals($expected,$actual);

        $start = new \DateTime('2019-03-01');
        TDates::IncrementToDOW($start,6,1);
        $expected = '2019-03-01';
        $actual = $start->format('Y-m-d');
        $this->assertEquals($expected,$actual);

        $start = new \DateTime('2019-03-01');
        TDates::IncrementToDOW($start,1,1);
        $expected = '2019-03-03';
        $actual = $start->format('Y-m-d');
        $this->assertEquals($expected,$actual);

        $start = new \DateTime('2019-03-01');
        TDates::IncrementToDOW($start,1,3);
        $expected = '2019-03-17';
        $actual = $start->format('Y-m-d');
        $this->assertEquals($expected,$actual);



    }

}
