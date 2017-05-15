<?php
/**
 * Created by PhpStorm.
 * User: terry
 * Date: 5/15/2017
 * Time: 11:53 AM
 */

use Tops\sys\TSession;
use PHPUnit\Framework\TestCase;

class TSessionTest extends TestCase
{
    public function testGetSet() {
        $key = 'TEST';
        $expected = 'FOO';
        TSession::Set($key,$expected);
        $actual= TSession::Get($key);
        $this->assertEquals($expected,$actual);
    }

    public function testSecurityToken() {
        $sessionToken = TSession::GetSecurityToken();
        $cookieToken = $_COOKIE['peanutSecurity'];
        $this->assertEquals($sessionToken,$cookieToken,'Session and cookie tokens do not match.');
        $expected = TSession::AuthenitcateSecurityToken($cookieToken);
        $this->assertTrue($expected,'Token not authenticated.');
        print "Security Token = $cookieToken";
    }
}
