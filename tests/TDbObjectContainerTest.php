<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 5/28/2018
 * Time: 7:44 AM
 */

use Tops\db\TDbObjectContainer;
use PHPUnit\Framework\TestCase;

class TDbObjectContainerTest extends TestCase
{

    public function testGetDbObject()
    {
        $container = new TDbObjectContainer();
        $actual = $container->get('test');
        $this->assertNotNull($actual);

    }
}
