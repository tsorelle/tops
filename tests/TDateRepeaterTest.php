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

    public function testDayPattern()
    {

        $month = 1;
        $year = 2018;

        $repeat = 'dd5;2017-12-28';
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertNotEmpty($actual);
        $this->assertEquals('2018-01-02',$actual[0]);
        $this->assertEquals(sizeof($actual),7);

        $repeat = 'dd;2017-12-28';
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertNotEmpty($actual);
        $this->assertEquals('2017-12-31',$actual[0]);
        $this->assertEquals(sizeof($actual),35);

        $repeat = 'dd;2017-12-28,2018-01-10';
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertNotEmpty($actual);
        $this->assertEquals('2017-12-31',$actual[0]);
        $this->assertEquals(sizeof($actual),10);

    }

    public function testWeekdayPattern() {
        $month = 1;
        $year = 2018;

        $repeat = 'dw;2018-01-04,2018-01-31';
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertNotEmpty($actual);
        $this->assertEquals('2018-01-04',$actual[0]);
        $this->assertEquals(sizeof($actual),19);

        $repeat = 'dw;2017-01-26';
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertNotEmpty($actual);
        $this->assertEquals('2018-01-01',$actual[0]);
        $this->assertEquals(sizeof($actual),25);


    }

    public function testWeeklyPattern() {
        $month = 1;
        $year = 2018;

        $repeat = 'wk2,34;2017-12-26';
        $expected = [
            '2018-01-09',
            '2018-01-10',
            '2018-01-23',
            '2018-01-24',
        ];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertNotEmpty($actual);
        $this->assertEquals($expected,$actual);

        $repeat = 'wk2,34;2018-01-04,2018-01-31';
        $expected = [
            '2018-01-04',
            '2018-01-16',
            '2018-01-17',
            '2018-01-30',
        ];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertNotEmpty($actual);
        $this->assertEquals($expected,$actual);

        $repeat = 'wk1,34;2018-01-04';
        $expected = [
            '2018-01-04',
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

        $repeat = 'wk1,34;2017-12-24';
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

        $month = 2;
        $repeat = 'md30,1;2017-12-26';
        $expected = [
            '2018-01-30'
        ];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertNotEmpty($actual);
        $this->assertEquals($expected,$actual);

        $month = 1;

        $repeat = 'md15,1;2018-01-18';
        $expected = [
            // '2018-01-15'
        ];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        // $this->assertNotEmpty($actual);
        $this->assertEquals($expected,$actual);

        $repeat = 'md15,1;2018-01-10';
        $expected = [
            '2018-01-15'
        ];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        // $this->assertNotEmpty($actual);
        $this->assertEquals($expected,$actual);



        $repeat = 'md31,1;2017-12-18';
        $expected = [
            '2017-12-31',
            '2018-01-31'
        ];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertNotEmpty($actual);
        $this->assertEquals($expected,$actual);

        $repeat = 'md23,1;2017-12-26';
        $expected = [
            '2018-01-23'
        ];
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        $this->assertNotEmpty($actual);
        $this->assertEquals($expected,$actual);

        $dates = [
            '2017-09-24' => 1,
            '2017-10-29' => 0,
            '2017-11-26' => 0,
            '2017-12-31' => 1,
        ];
        foreach ($dates as $date => $expected) {
            $repeat = 'md23,3;'.$date;
            $repeater = new TDateRepeater();
            $actual = $repeater->getDates($year,$month,$repeat);
            $this->assertEquals($expected,sizeof($actual));
        }

    }


}
