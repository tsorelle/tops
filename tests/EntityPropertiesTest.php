<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 6/5/2018
 * Time: 11:53 AM
 */

use Tops\db\EntityProperties;
use PHPUnit\Framework\TestCase;

class EntityPropertiesTest extends TestCase
{

    public function testGetDefinitions()
    {
        $properties = new EntityProperties('test');
        $actual = $properties->getDefinitions();
        $this->assertNotNull($actual);
    }

    public function testGetLookups()
    {
        $properties = new EntityProperties('test');
        $actual = $properties->getLookups();
        $this->assertNotNull($actual);

    }

    public function testGetValues()
    {
        $properties = new EntityProperties('test');
        $actual = $properties->getValues(1);
        $this->assertNotNull($actual);
    }

    public function testValidate()
    {
        $properties = new EntityProperties('test');
        $values = [
            'one' => '',
            'two' => null,
            'three' => 'three',
            'four' => [
                    '0' => 31,
                    '1' => 32,
                ],
            'five' => 'not int'
        ];

        $actual = $properties->validate($values);
        $this->assertTrue(is_array($actual));
        $this->assertEquals(4,sizeof($actual));

        $values['one'] = 'one';
        $values['two'] = 2;
        $values['three'] = 3.3;
        $values['five'] = 5;

        $actual = $properties->validate($values);
        $this->assertTrue($actual === true);

    }

    public function testSetClearValues() {
        $properties = new EntityProperties('test');
        $properties->dropValues(1);
        $defaults = $properties->getEmptyValues();
        $values= $properties->getValues(1);
        $this->assertTrue($defaults == $values);
        $values = [
            'one' => 'one',
            'two' => '2',
            'three' => '3.3',
            'four' => [
                '0' => '31',
                '1' => '32',
            ],
            'five' => '5'
        ];

        $properties->setValues(1,$values);
        $actual = $properties->getValues(1);
        $this->assertTrue($values == $actual);
        $properties->dropValues(1);

    }
}
