<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 9/5/2017
 * Time: 6:44 AM
 */

use Tops\mail\TMailConfiguration;
use PHPUnit\Framework\TestCase;
use Tops\sys\TConfiguration;

class TMailConfigurationTest extends TestCase
{

    public function testSmtpSettings() {
        TConfiguration::reset();
        TConfiguration::loadAppSettings('mailtest.ini');
        $this->assertTrue(TConfiguration::isValid());

        $settings = TMailConfiguration::GetSettings();
        $this->assertNotNull($settings);

        $expected = 'smtp';
        $actual = $settings->sendmail;
        $this->assertEquals($expected,$actual);

        $expected = 'mail.example.com';
        $actual = $settings->host;
        $this->assertEquals($expected,$actual);

        $expected = 25;
        $actual = $settings->port;
        $this->assertEquals($expected,$actual);

        $expected = 0;
        $actual = $settings->debug;
        $this->assertEquals($expected,$actual);

        $actual = $settings->auth;
        $this->assertTrue($actual,'Auth should be true');

        $expected = 'testuser';
        $actual = $settings->username;
        $this->assertEquals($expected,$actual);

        $expected = 'testpwd';
        $actual = $settings->password;
        $this->assertEquals($expected,$actual);

    }
    public function testDefaultSettings() {
        TConfiguration::reset();
        TConfiguration::loadAppSettings('use defaults');
        $this->assertTrue(TConfiguration::isValid());

        $settings = TMailConfiguration::GetSettings();
        $this->assertNotNull($settings);

        $expected = 1;
        $actual = $settings->sendmail;
        $this->assertEquals($expected,$actual);

        $expected = 0;
        $actual = $settings->debug;
        $this->assertEquals($expected,$actual);

        $actual = $settings->auth;
        $this->assertFalse($actual,'Auth should be false');

    }
    
    public function testGetIniEmailValues() {
        TConfiguration::reset();
        TConfiguration::loadAppSettings('mailtest.ini');
        $this->assertTrue(TConfiguration::isValid());

        $settings = TMailConfiguration::GetIniEmailValues('list1','mail-lists');
        $this->assertNotNull($settings);
        $this->assertNotEmpty($settings);
        $this->assertEquals(2,sizeof($settings));

        $expected = 'terry@foo.com';
        $actual = $settings[0];
        $this->assertEquals($expected,$actual);

        $expected = 'web@master.com';
        $actual = $settings[1];
        $this->assertEquals($expected,$actual);
    }
}
