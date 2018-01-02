<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 1/2/2018
 * Time: 10:45 AM
 */

use Tops\db\model\entity\ProcessLogEntry;
use PHPUnit\Framework\TestCase;

class ProcessLogEntryTest extends TestCase
{

    public function testAssignFromObject()
    {
        $instance = new ProcessLogEntry();
        $dto = new stdClass();
        $dto->id = 1;
        $dto->processCode = 'code';
        // $dto->posted = '';
        $dto->event = 'event';
        $dto->message = 'message';
        $dto->messageType = 'type';
        $dto->detail = 'detail';

        $instance->assignFromObject($dto);

        $this->assertEquals($dto->processCode,$instance->processCode);
        $this->assertEquals($dto->event ,$instance->event           );
        $this->assertEquals($dto->message ,$instance->message       );
        $this->assertEquals($dto->messageType,$instance->messageType);
        $this->assertEquals($dto->detail ,$instance->detail         );
        $this->assertNotEmpty($instance->posted);
    }
}
