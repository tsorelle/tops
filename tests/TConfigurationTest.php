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

    public function testBasicAppSettings()
    {
        TConfiguration::clearCache();
        $expected = 'services';
        $actual = TConfiguration::getIniValue('servicesNamespace', 'classes');
        $this->assertNotNull($actual);
        $this->assertEquals($expected, $actual);
    }
    public function testGetSection()
    {
        TConfiguration::clearCache();
        TConfiguration::loadAppSettings('settings.ini,database.ini,test.ini');

        $expected = 3;
        $section = TConfiguration::getIniSection('database');
        $this->assertNotNull($section);
        $actual = sizeof($section);
        $this->assertEquals($expected, $actual);
    }

    public function testMultipleAppSettings()
    {
        TConfiguration::clearCache();
        TConfiguration::loadAppSettings('settings.ini,database.ini,test.ini');

        $expected = 'services';
        $actual = TConfiguration::getIniValue('servicesNamespace', 'classes');
        $this->assertNotNull($actual);
        $this->assertEquals($expected, $actual);

        $expected = 'topuser';
        $actual = TConfiguration::getIniValue('username', 'database');
        $this->assertNotNull($actual);
        $this->assertEquals($expected, $actual);

        $expected = 'ok';
        $actual = TConfiguration::getIniValue('loaded', 'test-values');
        $this->assertNotNull($actual);
        $this->assertEquals($expected, $actual);


    }
}
