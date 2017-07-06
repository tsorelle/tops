<?php
/**
 * Created by PhpStorm.
 * User: terry
 * Date: 5/15/2017
 * Time: 11:05 AM
 */

use Tops\sys\TUser;
use PHPUnit\Framework\TestCase;

class TUserTest extends TestCase
{
    public function testCreateUser() {
        $projectFileRoot =   str_replace('\\','/', realpath(__DIR__.'/..')).'/';
        \Tops\sys\TPath::Initialize($projectFileRoot,'tests/config');

        $user = TUser::getCurrent();
        $this->assertNotNull($user);
        $expected = 'tester';
        $actual = $user->getUserName();
        $this->assertEquals($expected,$actual);
        $actual = $user->isAuthorized('donations.view');
        $this->assertTrue($actual);
        $actual = $user->isAuthorized('donations.edit');
        $this->assertTrue($actual);
        $actual = $user->isAuthorized('testpermission');
        $this->assertFalse($actual);
        $actual = $user->isAuthorized('nosuchpermission');
        $this->assertFalse($actual);

        $user = TUser::getByUserName('admin');
        $this->assertNotNull($user);
        $expected = 'admin';
        $actual = $user->getUserName();
        $this->assertEquals($expected,$actual);
        $actual = $user->isAuthorized('donations.view');
        $this->assertTrue($actual);
        $actual = $user->isAuthorized('donations.edit');
        $this->assertTrue($actual);
        $actual = $user->isAuthorized('testpermission');
        $this->assertTrue($actual);
        $actual = $user->isAuthorized('nosuchpermission');
        $this->assertTrue($actual);
    }
}
