<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 1/2/2018
 * Time: 11:33 AM
 */

use Tops\db\model\entity\VariableEntity;
use PHPUnit\Framework\TestCase;

class VariableEntityTest extends TestCase
{
    public function testAssignVariable() {
        $instance = new VariableEntity();
        $dto = new \stdClass();
        $dto->name = 'name';
        $dto->code = 'code';
        $dto->description = 'description';

        $instance->assignFromObject($dto);
        $this->assertEquals($dto->name ,		$instance->name);
        $this->assertEquals($dto->code ,        $instance->code);
        $this->assertEquals($dto->description,  $instance->description);
//        $this->assertNotEmpty($instance->createdon);
//        $this->assertNotEmpty($instance->changedon);
//        $this->assertEquals(0,$instance->id);

//        $created = $instance->createdon;
//        $createdBy = $instance->createdby;

        $dto->changedby='terry';
        $dto->id = 3;

        $instance->assignFromObject($dto);
        $this->assertEquals($dto->name ,		$instance->name);
        $this->assertEquals($dto->code ,        $instance->code);
        $this->assertEquals($dto->description,  $instance->description);
//        $this->assertNotEmpty($instance->createdon);
//        $this->assertNotEmpty($instance->changedon);
        $this->assertEquals(3,$instance->id);
        $this->assertEquals($dto->changedby,$instance->changedby);
//        $this->assertEquals($created,$instance->createdon);
//        $this->assertEquals($createdBy,$instance->createdby);
        // $this->assertNotEquals($created,$instance->changedon);

    }

}
