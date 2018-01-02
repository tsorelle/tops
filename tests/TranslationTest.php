<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 1/2/2018
 * Time: 11:22 AM
 */

use Tops\db\model\entity\Translation;
use PHPUnit\Framework\TestCase;

class TranslationTest extends TestCase
{

    public function testAssignDto()
    {
        $instance = new Translation();
        $dto = new stdClass();
        $dto->language = 'en-Us';
        $dto->code = 'CODE';
        $dto->text = 'TEXT';
        $dto->createdby = 'user';
        $dto->createdon = '9/12/1947';
        $dto->changedby = 'terry';

        $instance->assignFromObject($dto,'terry');

        $this->assertEquals($dto->language,  $instance->language);
        $this->assertEquals($dto->code,      $instance->code);
        $this->assertEquals($dto->text,      $instance->text);
        $this->assertEquals(1,      $instance->active);
    }
}
