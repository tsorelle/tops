<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 1/15/2018
 * Time: 9:45 AM
 */

use Tops\db\TDownloadManager;

class TDownloadManagerTest extends PHPUnit_Framework_TestCase
{

    public function disabledtestGetCsvData()
    {

    }


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
        $manager = new TDownloadManager();
        $actual = $manager->objectsToCsv($objs,['Address' => 'number']);
        $this->assertNotEmpty($actual);

    }
}
