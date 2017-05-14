<?php
/**
 * Created by PhpStorm.
 * User: terry
 * Date: 5/14/2017
 * Time: 6:00 AM
 */

use PHPUnit\Framework\TestCase;

class BootTest extends TestCase
{
    public function testAutoloadTops() {
        $actual = new \Tops\sys\ConfigurationManager();
        $this->assertNotNull($actual,'cannot load CofigurationManager class');
    }

    public function testAutoloadTesting() {
        $actual= new \TwoQuakers\testing\AutoloadTestClass();
        $this->assertNotNull($actual,'Cannot load test class');
    }
}
