<?php
/**
 * Created by PhpStorm.
 * User: terry
 * Date: 5/15/2017
 * Time: 9:40 AM
 */

use Tops\sys\TConfiguration;
use PHPUnit\Framework\TestCase;

class TConfigurationTest extends TestCase
{

    /*
    public function testBasicAppSettings()
    {
        TConfiguration::clearCache();
        $expected = 'services';
        $actual = TConfiguration::getValue('servicesNamespace', 'services');
        $this->assertNotNull($actual);
        $this->assertEquals($expected, $actual);
    }
    */

    public function testGetSection()
    {
        TConfiguration::clearCache();
        TConfiguration::loadAppSettings('settings.ini,test.ini');

        $expected = 5;
        $section = TConfiguration::getIniSection('test-values');
        $this->assertNotNull($section);
        $actual = sizeof($section);
        $this->assertEquals($expected, $actual);
    }

    public function testMultipleAppSettings()
    {
        TConfiguration::clearCache();
        TConfiguration::loadAppSettings('settings.ini,test.ini');
/*
        $expected = 'services';
        $actual = TConfiguration::getValue('servicesNamespace', 'services');
        $this->assertNotNull($actual);
        $this->assertEquals($expected, $actual);
*/
        $expected = 'ok';
        $actual = TConfiguration::getValue('loaded', 'test-values');
        $this->assertNotNull($actual);
        $this->assertEquals($expected, $actual);


    }
    
    public function testBoolSetting() {
        $actual = TConfiguration::getBoolean('trueValue','test',false);
        $this->assertTrue($actual);

        $actual = TConfiguration::getBoolean('falseValue','test',true);
        $this->assertFalse($actual);

        $actual = TConfiguration::getBoolean('unassignedValue','test',true);
        $this->assertTrue($actual);

        $actual = TConfiguration::getBoolean('unassignedValue','test',false);
        $this->assertFalse($actual);

    }
}
