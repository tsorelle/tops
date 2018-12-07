<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 12/7/2018
 * Time: 6:58 AM
 */

use Tops\sys\TInterval;
use PHPUnit\Framework\TestCase;

class TIntervalTest extends TestCase
{
    private $units = [
        'PT3S' => 'seconds',
        'PT3M' => 'minutes',
        'PT3H' => 'hours',
        'P3D' => 'days',
        'P3W' => 'weeks',
        'P3M' => 'months',
        'P3Y' => 'years'
    ];

    public function testStringToInterval()
    {
        foreach ($this->units as $expected => $unit) {
            $s = '3 '.$unit;
            $actual = TInterval::stringToInterval($s);
            $this->assertNotEmpty($actual);
        }

    }

    public function testStringToIntervalSpec() {
        $s = '7 weeks, 2 days, 3 hours and 7 minutes';
        $expected = 'P7W2DT3H7M';
        // $i = new \DateInterval($expected);
        // $this->assertNotNull($i);
        $actual = TInterval::stringToIntervalSpec($s);
        $this->assertEquals($expected,$actual);

    }

    public function testIntervalToString()
    {
        foreach ($this->units as $interval => $unit) {
            $expected = '3 '. $unit;
            $actual = TInterval::intervalToString($interval);
            $this->assertNotEmpty($actual);
            $this->assertEquals($actual, $expected);
        }


    }
    public function testIntervalToLongString()
    {
        $interval = 'P5M10DT13H20M';
        $expected = '5 months, 10 days, 13 hours and 20 minutes';
        $actual = TInterval::intervalToString($interval);
        $this->assertEquals($expected,$actual);

        $interval = 'PT120M';
        $expected = '120 minutes';
        $actual = TInterval::intervalToString($interval);
        $this->assertEquals($expected,$actual);

        $interval = 'PT120M15S';
        $expected = '120 minutes and 15 seconds';
        $actual = TInterval::intervalToString($interval);
        $this->assertEquals($expected,$actual);

        $interval = 'P10M14D';
        $expected = '10 months and 14 days';
        $actual = TInterval::intervalToString($interval);
        $this->assertEquals($expected,$actual);

        $interval = 'P10M';
        $expected = '10 months';
        $actual = TInterval::intervalToString($interval);
        $this->assertEquals($expected,$actual);
    }
}
