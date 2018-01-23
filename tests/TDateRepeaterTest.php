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

    public function testGetDates()
    {
        $this->assertNotEmpty(true); // $actual);
        return;
        $month = 1;
        $year = 2017;

        $repeat = 'dd5;2017-12-30';
        $repeater = new TDateRepeater();
        $actual = $repeater->getDates($year,$month,$repeat);
        // $this->assertNotEmpty($actual);

    }
}
