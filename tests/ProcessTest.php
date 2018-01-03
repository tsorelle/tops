<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 1/2/2018
 * Time: 11:07 AM
 */

use Tops\db\model\entity\Process;
use PHPUnit\Framework\TestCase;
use Tops\sys\TDates;

class ProcessTest extends TestCase
{

    public function testAssignFromObject()
    {
        $dto = new stdClass();
        $dto->description = 'description';

        $instance = new Process();
        $errors = $instance->assignFromObject($dto);
        $this->assertEquals(2,sizeof($errors));

        $dto->code = 'code';
        $dto->name = 'name';
        $instance = new Process();
        $errors = $instance->assignFromObject($dto);
        $this->assertEmpty($errors);


        $this->assertEquals($dto->code,        $instance->code);
        $this->assertEquals($dto->name,        $instance->name);
        $this->assertEquals($dto->description, $instance->description);
        $this->assertEmpty($instance->paused);
        $this->assertEquals(1,$instance->enabled);

        $paused = '9/12/2018 0:12:59';
        $expected = TDates::formatMySqlDate($paused,true);
        $dto->paused = $paused;
        $instance = new Process();
        $errors = $instance->assignFromObject($dto);
        if (!empty($errors)) {
            print_r($errors);
        }
        $this->assertEmpty($errors);
        $this->assertEquals($expected,$instance->paused);
    }
}
