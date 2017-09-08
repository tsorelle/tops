<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 9/8/2017
 * Time: 4:14 PM
 */

use Tops\mail\TPostOffice;
use PHPUnit\Framework\TestCase;

class TPostOfficeTest extends TestCase
{
    public function testCreateMessage() {
        $mailboxes = new \TwoQuakers\testing\FakeMailboxManager();
        $mailboxes->addMailbox('bounce','Bounce box','bounce@test.com','');
        $mailboxes->addMailbox('support','Support','support@test.com','');
        $mailer = new \Tops\mail\TNullMailer();
        $po = new TPostOffice($mailer,$mailboxes);
        TPostOffice::setInstance($po);
        $msg = TPostOffice::CreateMessageFromUs();
        $this->assertNotNull($msg);
        $from = $msg->getFromAddress();
        $actual = $from->getName();
        $expected = 'Support';
        $this->assertEquals($expected,$actual);
        $actual = $from->getAddress();
        $expected = 'support@test.com';
        $this->assertEquals($expected,$actual);
    }

}
