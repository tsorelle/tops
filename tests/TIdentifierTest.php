<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 12/6/2018
 * Time: 7:08 AM
 */

use Tops\sys\TIdentifier;
use PHPUnit\Framework\TestCase;

class TIdentifierTest extends TestCase
{

    public function testIsValid()
    {
        $values = [
            'c5c03a0c-f94f-11e8-beeb-b4b67682633d',
            '6168760a-f958-11e8-beeb-b4b67682633d',
            '7446d01e-f958-11e8-beeb-b4b67682633d',
            '7d4337d2-f958-11e8-beeb-b4b67682633d',
        ];
        $badvalues = [
            'c5c03a0c-WXYZ-11e8-beeb-b4b67682633d',
            'c5c03a0c-11e8-beeb-b4b67682633d-c5c03a0c-11e8-beeb-b4b67682633d',
            'c5c03a0c-WXYZ-11e8-beeb',
            'WHAT IS THIS',
            '0393094093920392afddasfdafaafaaafaafad03928933891289281979',
            '0393',
            '',
            null,
            0,
            false
        ];

        foreach ($values as $value) {
            $actual = TIdentifier::IsValid($value);
            $this->assertTrue($actual,"$value should be ok.");
        }
        foreach ($badvalues as $value) {
            $actual = TIdentifier::IsValid($value);
            $this->assertFalse($actual,"$value should fail.");
        }

        $actual = TIdentifier::IsValid('',true);
        $this->assertTrue($actual,"blank value should be ok.");

        $actual = TIdentifier::IsValid(null,true);
        $this->assertTrue($actual,"Null value should be ok.");

        $actual = TIdentifier::IsValid(0,true);
        $this->assertFalse($actual,"Zero value should be fail.");

    }

    public function testNewId()
    {
        $ids = [];
        for ($i=0;$i<100;$i++) {
            $id = TIdentifier::NewId();
            $valid = TIdentifier::IsValid($id);
            $this->assertTrue($valid,"$id is not valid.");
            $unique = !in_array($id,$ids);
            $this->assertTrue($unique,"$id is not unique");
            $ids[] = $id;
        }
    }
}
