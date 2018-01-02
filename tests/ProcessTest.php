<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 1/2/2018
 * Time: 11:07 AM
 */

use Tops\db\model\entity\Process;
use PHPUnit\Framework\TestCase;

class ProcessTest extends TestCase
{

    public function testAssignFromObject()
    {
        $instance = new Process();
        $dto = new stdClass();
//        $dto->id = 0;
        $dto->code = 'code';
        $dto->name = 'name';
        $dto->description = 'description';
//        $dto->paused =
//        $dto->enabled

        $instance->assignFromObject($dto);
        $this->assertEquals($dto->code,        $instance->code);
        $this->assertEquals($dto->name,        $instance->name);
        $this->assertEquals($dto->description, $instance->description);
        $this->assertEmpty($instance->paused);
        $this->assertEquals(1,$instance->enabled);
    }
}
