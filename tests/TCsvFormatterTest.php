<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 1/15/2018
 * Time: 9:45 AM
 */


class TCsvFormatterTest extends \PHPUnit\Framework\TestCase
{
    private function createTestObj($name,$address,$city) {
        $result = new stdClass();
        $result->Name = $name;
        $result->Address = $address;
        $result->City = $city;
        return $result;
    }

    public function testObjectsToCsv()
    {
        $objs = [];
        $objs[] = $this->createTestObj('Terry',1,'Austin');
        $objs[] = $this->createTestObj('Joe',2,'Boston');
        $actual = \Tops\sys\TCsvFormatter::ToCsv($objs,['Address' => 'number']);
        $this->assertNotEmpty($actual);
        $expected = ['"Name","Address","City"','"Terry",1,"Austin"','"Joe",2,"Boston"'];
        $this->assertEquals($expected,$actual);
    }
}
