<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 8/30/2017
 * Time: 8:08 AM
 */

use Tops\mail\TSayersEmailValidator;
use PHPUnit\Framework\TestCase;

class TSayersEmailValidatorTest extends TestCase
{

    public function testValid() {
        $emails = array('tls@2quakers.net','terry.sorelle@outlook.com','Terry.SoRelle@outlook.com','Terry.SoRelle@songs.2quakers.net');
        foreach ($emails as $email) {
            $actual	=  TSayersEmailValidator::is_email($email,false,true);
            $expected = 0;
            $this->assertEquals($expected,$actual,$email);
        }
    }


    public function testInvalid() {
        $emails = array('tls.net','wtfisthis','Terry.SoRelle@','terry%foo@$$#');
        foreach ($emails as $email) {
            $actual	=  TSayersEmailValidator::is_email($email,false,true);
            $success = 0;
            $this->assertNotEquals($success,$actual,$email);
        }

    }



}
