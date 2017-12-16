<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 8/30/2017
 * Time: 9:55 AM
 */

use Tops\mail\TEmailValidator;
use PHPUnit\Framework\TestCase;

class TEmailValidatorTest extends TestCase
{
    public function testValid() {
        $emails = array('Terry SoRelle <tls@2quakers.net>', 'tls@2quakers.net','terry.sorelle@outlook.com','Terry.SoRelle@outlook.com','Terry.SoRelle@songs.2quakers.net','terry%foo@$$#');
        foreach ($emails as $email) {
            $validator = TEmailValidator::Create();
            $isvalid =  $validator->isValid($email);
            $actual = $isvalid ? 'ok' : $validator->getError();
            $expected = 'ok';
            $this->assertEquals($expected,$actual,$email);
            $warnings = $validator->getWarnings();
            if (!empty($warnings)) {
                foreach ($warnings as $warning) {
                    print "Warning for $email: $warning\n";
                }
            }
        }
    }


    public function testInvalid() {
        $emails = array('tls.net','wtfisthis','Terry.SoRelle@');
        foreach ($emails as $email) {
            $validator = TEmailValidator::Create();
            $isvalid =  $validator->isValid($email);
            $actual = $isvalid ? 'ok' : $validator->getError();
            $result = $validator->getResultCode();
            $expected = 'ok';
            $this->assertNotEquals($expected,$actual,$email);
            $warnings = $validator->getWarnings();
            $this->assertEmpty($warnings,'Warnings: '.join(',',$warnings));
        }

    }

    public function testDnsFail() {
        $emails = array('me@nodomain.foo','foo<badformat');
        foreach ($emails as $email) {
            $validator = TEmailValidator::Create();
            $isvalid =  $validator->isValid($email,true,true);
            $actual = $isvalid ? 'ok' : $validator->getError();
            $result = $validator->getResultCode();
            $expected = 'ok';
            $this->assertNotEquals($expected,$actual,$email);
            $error = $validator->getError();
            print "Actual result for $email '$error'\n";
            $warnings = $validator->getWarnings();
            $this->assertEmpty($warnings,'Warnings: '.join(',',$warnings));
        }
    }

    public function testDnsCheck() {
        $emails = array('tls@2quakers.net' => true,'me@nodomain.foo' => false,'foo<badformat' => false,
            'ery2@2quakers.net' => true,'nobody@2quakers.net' => true,'axmurderer@microsoft.com' => true);
        foreach ($emails as $email => $expected) {
            $validator = TEmailValidator::Create();
            $actual = $validator->checkDns($email);
            $this->assertEquals($expected,$actual,$email);
        }
    }





}
