<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 2/26/2019
 * Time: 5:26 AM
 */

use Tops\sys\TNameValuePair;
use PHPUnit\Framework\TestCase;

class TNameValuePairTest extends TestCase
{

    public function testExpandArray()
    {
        $a = ['one','two','three'];
        $actual = TNameValuePair::FromArray($a,1);
        foreach ($actual as $item) {
            $this->assertEquals(
                $item->Name,$a[$item->Value - 1]
            );
        }
    }
}
