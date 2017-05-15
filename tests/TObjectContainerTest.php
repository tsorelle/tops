<?php
/**
 * Created by PhpStorm.
 * User: terry
 * Date: 5/15/2017
 * Time: 10:57 AM
 */

use Tops\sys\TObjectContainer;
use PHPUnit\Framework\TestCase;

class TObjectContainerTest extends TestCase
{
    public function testObjectCreation() {
        $actual = TObjectContainer::Get('testclass');
        $this->assertInstanceOf('\TwoQuakers\testing\AutoloadTestClass' ,$actual);

        $actual = TObjectContainer::HasDefinition('testclass');
        $this->assertTrue($actual);
        $actual = TObjectContainer::Get('testclass');
        $this->assertInstanceOf('\TwoQuakers\testing\AutoloadTestClass' ,$actual);
    }
}
