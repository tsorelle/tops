<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 12/28/2017
 * Time: 7:05 AM
 */

use Tops\sys\TDataTransfer;
use PHPUnit\Framework\TestCase;
use Tops\sys\TDates;
use Tops\sys\TNameValuePair;
use TwoQuakers\testing\TestDto;

class TDataTransferTest extends TestCase
{
    public function testAssignValue() {
        $dto = new \stdClass();
        $obj = new TNameValuePair();
        $dt = new TDataTransfer($dto,$obj);
        $expected = 'test';
        $dto->Value = $expected;
        $dt->assignValue('Value');
        $actual = $obj->Value;
        $this->assertEquals($expected,$actual);

        $actual = $dto->Value;
        $this->assertEquals($expected,$actual);
    }

    public function testAssignAll() {
        $dtoDateTime = '9/12/1947 1:23 pm';
        $expectedDateTime = '1947-09-12 13:23:00';

        $dtoDate = '9/12/1947';
        $expectedDate = '1947-09-12';
        $expectedDefault = 'Default';
        $expectedDeault2 = 'foo';

        $today = Date(TDates::MySqlDateFormat);
        $now = Date('Y-m-d H:i');

        $obj = new TestDto();
        $dto = new stdClass();
        $dto->id = 10;
        $dto->name = 'Name';
        $dto->dateField = $dtoDate;
        $dto->dateTimeField = $dtoDateTime;
        $dto->active = 0;
        $dto->flag = true;
        $dto->emptyDate1 = '';
        $dto->emptyDate2 = '0000-00-00';
        $dto->emptyDate3 = null;
        $dto->ignored = 'ignore this';
        $dto->defaultValue2 = $expectedDeault2;


        $dt = new TDataTransfer($dto,$obj,[
            'dateField'=>'date',
            'dateTimeField'=>'datetime',
            'flag'=>'flag'
            ,'falseFlag'=>'flag'
            // ,'today'=>'today' // today assigned by defaults
            ,'now'=>'now'  // now updated if supplied, timestamped otherwise.
            ,'emptyDate1' => 'date'
            ,'emptyDate2' => 'datetime'
            ,'emptyDate3' => 'date'
        ]);
        $dt->assignAll();
        $dt->assignDefaultValues([
            'today' => 'today',
            'defaultValue' => $expectedDefault,
            'defaultValue2' => $expectedDefault  // overridden since pre-assigned
        ]);

        $expected = !property_exists($obj,'ignored');
        $this->assertEquals($expected,true);
        $expected = isset($obj->ignored);
        $this->assertEquals($expected,false);

        $this->assertEquals($dto->id             ,$obj->id           );
        $this->assertEquals($dto->name           ,$obj->name         );
        $this->assertEquals($dto->active         ,0);
        $this->assertEquals($dto->flag           ,1);
        $this->assertEquals($obj->dateField,    $expectedDate);
        $this->assertEquals($obj->dateTimeField  ,$expectedDateTime);

        $this->assertTrue($obj->emptyDate1 === null);
        $this->assertTrue($obj->emptyDate2 === null);
        $this->assertTrue($obj->emptyDate3 === null);

        $this->assertEquals($expectedDefault, $obj->defaultValue);
        $this->assertEquals($obj->defaultValue2, $obj->defaultValue2);
        $this->assertEquals($today, $obj->today);
        $actual = substr($obj->now,0,strlen($now));
        $this->assertEquals($now, $actual);
    }

}
