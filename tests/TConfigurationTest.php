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
    public function tearDown() {
        // restore configuration to original state.
        TConfiguration::reset();
    }

    public function testGetSection()
    {
        TConfiguration::reset();
        TConfiguration::loadAppSettings('settings.ini,test.ini');

        $expected = 5;
        $section = TConfiguration::getIniSection('test-values');
        $this->assertNotNull($section);
        $actual = sizeof($section);
        $this->assertEquals($expected, $actual);
        $this->assertTrue(TConfiguration::isValid());
    }

    public function testMultipleAppSettings()
    {
        TConfiguration::reset();
        TConfiguration::loadAppSettings('settings.ini,test.ini');
        $this->assertTrue(TConfiguration::isValid());

        $expected = 'ok';
        $actual = TConfiguration::getValue('loaded', 'test-values');
        $this->assertNotNull($actual);
        $this->assertEquals($expected, $actual);

        $expected = 'new';
        $actual = TConfiguration::getValue('replacevalue', 'test');
        $this->assertNotNull($actual);
        $this->assertEquals($expected, $actual);

        $expected = '1';
        $actual = TConfiguration::getBoolean('newvalue','test');
        $this->assertTrue($actual);




    }
    
    public function testBoolSetting() {
        TConfiguration::reset();
        $actual = TConfiguration::getBoolean('trueValue','test',false);
        $this->assertTrue($actual);

        $actual = TConfiguration::getBoolean('falseValue','test',true);
        $this->assertFalse($actual);

        $actual = TConfiguration::getBoolean('unassignedValue','test',true);
        $this->assertTrue($actual);

        $actual = TConfiguration::getBoolean('unassignedValue','test',false);
        $this->assertFalse($actual);
        $this->assertTrue(TConfiguration::isValid());

    }

    public function testEmptyIniFiles() {
        TConfiguration::reset();
        TConfiguration::loadAppSettings('nofile,somefile,invalidfile');
        $actual = TConfiguration::hasErrors();
        $this->assertTrue($actual,'Should have errors');
        $actual = TConfiguration::getErrors();
        $this->assertNotEmpty($actual,'No errors returned');
        $this->assertEquals(sizeof($actual),3,'Error count wrong');
        $this->assertTrue(TConfiguration::isValid());
    }

    public function testInvalidFile() {
        TConfiguration::reset();
        TConfiguration::throwExceptions(false);
        TConfiguration::loadAppSettings('empty.ini,invalid.ini');
        $this->assertFalse(TConfiguration::isValid());
        $fatals = TConfiguration::getFatalErrors();
        self::assertNotEmpty($fatals);
        $errors = TConfiguration::getErrors();
        $this->assertNotEmpty($errors);
        $expected = sizeof($fatals) + 1;
        $actual = sizeof($errors);
        $this->assertEquals($expected,$actual);

    }

    public function testExceptions() {
        TConfiguration::reset();
        TConfiguration::requireFiles();
        $exceptionOccured = false;
        try {
            TConfiguration::loadAppSettings('empty.ini');
        }
        catch (\Exception $ex) {
            $exceptionOccured = true;
        }
        $this->assertTrue($exceptionOccured,'No exception');

        $exceptionOccured = false;
        try {
            TConfiguration::loadAppSettings('invalid.ini');
        }
        catch (\Exception $ex) {
            $exceptionOccured = true;
        }
        $this->assertTrue($exceptionOccured,'No exception');

    }

    public function testMultipleValues() {
        TConfiguration::reset();
        TConfiguration::loadAppSettings('multivalue.ini');
        $this->assertTrue(TConfiguration::isValid());

        $values = TConfiguration::getMultipleValues('test','test-values');
        $this->assertNotEmpty($values);

        $expected = 'one';
        $actual = $values[0];
        $this->assertEquals($expected,$actual);

        $expected = 'Ok';
        $actual = $values[2];
        $this->assertEquals($expected,$actual);

        $this->assertTrue(is_array($values[1]));
        $this->assertEquals(3,sizeof($values[1]));

        $expected = 1;
        $actual = $values[1]['one'];
        $this->assertEquals($expected,$actual);

        $expected = 2;
        $actual = $values[1]['two'];
        $this->assertEquals($expected,$actual);

        $expected = 3;
        $actual = $values[1]['three'];
        $this->assertEquals($expected,$actual);

    }

}
