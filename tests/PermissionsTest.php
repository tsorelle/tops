<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 9/11/2017
 * Time: 5:25 AM
 */

use PHPUnit\Framework\TestCase;
use TwoQuakers\testing\FakePermissionsManager;

class PermissionsTest extends TestCase
{
    public function testTPermission() {
        $manager = new FakePermissionsManager();

        $manager->addPermission('edit','Can change text');

        $manager->assignPermission('admin','edit');
        $manager->assignPermission('member','edit');

        $permission = $manager->getPermission('edit');
        $expected = 'Can change text';
        $actual = $permission->getDescription();
        $this->assertEquals($expected, $actual);

        $actual = $permission->check('admin');
        $this->assertTrue($actual,'Admin should have edit');

        $actual = $permission->check('member');
        $this->assertTrue($actual,'Member should have edit');

        $actual = $permission->check('reader');
        $this->assertFalse($actual,'Reader should not have edit');

        $actual = $permission->check('Guest');
        $this->assertFalse($actual,'Guest should not have edit');




    }

}
