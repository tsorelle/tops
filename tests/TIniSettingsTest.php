<?php
/**
 * Created by PhpStorm.
 * User: terry
 * Date: 7/27/2017
 * Time: 5:20 AM
 */

use PHPUnit\Framework\TestCase;

class TIniSettingsTest extends TestCase {
    public function testGetSection()
    {
        $ini = \Tops\sys\TIniSettings::Create('test.ini');
        $expected = 5;
        $section = $ini->getSection('test-values');
        $this->assertNotNull($section);
        $actual = sizeof($section);
        $this->assertEquals($expected, $actual);
    }

    public function testGetValue() {
        $ini = \Tops\sys\TIniSettings::Create('test.ini');
        $actual = $ini->getValue('somevalue','test-values');
        $expected = 'success';
        $this->assertEquals($expected,$actual);
    }

    public function testDefaultValue() {
        $ini = \Tops\sys\TIniSettings::Create('test.ini');
        $actual = $ini->getValue('nosuchvalue','test-values','not found');
        $expected = 'not found';
        $this->assertEquals($expected,$actual);
    }

    public function testBoolSetting() {
        $ini = \Tops\sys\TIniSettings::Create('test.ini');
        $actual = $ini->getBoolean('trueValue','test-values',false);
        $this->assertTrue($actual);

        $actual = $ini->getBoolean('falseValue','test-values',true);
        $this->assertFalse($actual);

        $actual = $ini->getBoolean('unassignedValue','test-values',true);
        $this->assertTrue($actual);

        $actual = $ini->getBoolean('unassignedValue','test-values',false);
        $this->assertFalse($actual);

    }

}
