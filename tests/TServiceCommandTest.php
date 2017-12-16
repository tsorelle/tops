<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 5/18/2017
 * Time: 6:30 AM
 */

use Tops\services\TServiceCommand;
use PHPUnit\Framework\TestCase;
use TwoQuakers\testing\services\FakeInputHandler;
use Tops\services\ServiceFactory;
use Tops\services\ResultType;
use Tops\services\MessageType;
use Tops\services\TServiceResponse;

class TServiceCommandTest extends TestCase
{
    public function setUp()
    {
        \Tops\sys\TSession::Initialize();
    }

    /**
     * @param TServiceResponse $response
     * @param $text
     * @param bool $type
     * @return bool|\Tops\services\TServiceMessage
     */
    private function findMessage(TServiceResponse $response,$text,$type=false) {
        foreach ($response->Messages as $message) {
            if (($type === false || $message->MessageType === $type) && $message->Text == $text) {
                return $message;
            }
        }
        return false;
    }

    private function showMessages(TServiceResponse $response) {
        print "\nMessages:\n";
        foreach ($response->Messages as $message) {
            switch ($message->MessageType) {
                case MessageType::Info :    print 'Info:    '; break;
                case MessageType::Error :   print 'Error:   '; break;
                case MessageType::Warning : print 'Warning: '; break;
            }
            print $message->Text."\n";
        }
    }

    private function showDebugInfo($response) {
        if (!empty($response->debugInfo->message)) {
            print $response->debugInfo->message."\n";
        }
        if (!empty($response->debugInfo->location)) {
            print $response->debugInfo->location."\n";
        }
    }

    public function testHelloWorld() {
        $this->assertTrue(true);

        \Tops\sys\TConfiguration::reset();
        FakeInputHandler::setServiceId('HelloWorld');
        $response = ServiceFactory::Execute();
        $this->assertNotNull($response);
        $this->showDebugInfo($response);
        $expected = ResultType::Success;
        $actual = $response->Result;
        $this->assertEquals($expected,$actual,"Service failed.");
        $expected = 'Hello World';
        $message = $this->findMessage($response,$expected);
        $actual = $message !== false;
        $this->assertTrue($actual,"Message not found.");
        $actual = $message->Text;
        $this->assertEquals($expected,$actual);
        $expected = MessageType::Info;
        $actual = $message->MessageType;
        $this->assertEquals($expected,$actual,'Wrong message type.');
        $this->showMessages($response);
        $this->assertNotEmpty($response->Value,"No Value returned.");
        $this->assertNotEmpty($response->Value->message,"Value->message not assigned.");
        $expected = "Greatings earthlings.";
        $actual = $response->Value->message;
        $this->assertEquals($expected,$actual);

    }

    public function testHelloMars() {
        FakeInputHandler::setServiceId('test-package::HelloMars');
        $response = ServiceFactory::Execute();
        $this->assertNotNull($response);
        $this->showDebugInfo($response);
        $expected = ResultType::Success;
        $actual = $response->Result;
        $this->assertEquals($expected,$actual,"Service failed.");
        $expected = 'Hello Mars';
        $message = $this->findMessage($response,$expected);
        $actual = $message !== false;
        $this->assertTrue($actual,"Message not found.");
        $actual = $message->Text;
        $this->assertEquals($expected,$actual);
        $expected = MessageType::Info;
        $actual = $message->MessageType;
        $this->assertEquals($expected,$actual,'Wrong message type.');
        $this->showMessages($response);
        $this->assertNotEmpty($response->Value,"No Value returned.");
        $this->assertNotEmpty($response->Value->message,"Value->message not assigned.");
        $expected = "Greatings earthlings from the Big Giant Head.";
        $actual = $response->Value->message;
        $this->assertEquals($expected,$actual);

    }

    public function testServiceException() {
        FakeInputHandler::setServiceId('NoSuchCommand');
        $response = ServiceFactory::Execute();
        $this->assertNotNull($response);
        $this->showDebugInfo($response);
        $expected = ResultType::ServiceFailure;
        $actual = $response->Result;
        $this->assertEquals($expected,$actual,"Service should have failed.");
    }

    function testSubService() {
        \Tops\sys\TConfiguration::clearCache();
        FakeInputHandler::setServiceId('SubServices.HelloWorld');
        $response = ServiceFactory::Execute();
        $this->assertNotNull($response);
        $this->showDebugInfo($response);
        $expected = ResultType::Success;
        $actual = $response->Result;
        $this->assertEquals($expected,$actual,"Service failed.");
        $expected = 'Hello from the sub World';
        $message = $this->findMessage($response,$expected);
        $actual = $message !== false;
        $this->assertTrue($actual,"Message not found.");
        $actual = $message->Text;
        $this->assertEquals($expected,$actual);
        $expected = MessageType::Info;
        $actual = $message->MessageType;
        $this->assertEquals($expected,$actual,'Wrong message type.');
        $this->showMessages($response);
        $this->assertNotEmpty($response->Value,"No Value returned.");
        $this->assertNotEmpty($response->Value->message,"Value->message not assigned.");
        $expected = "Greatings earthlings.";
        $actual = $response->Value->message;
        $this->assertEquals($expected,$actual);

    }

}
