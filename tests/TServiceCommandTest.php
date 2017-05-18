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

    public function testHelloWorld() {
        FakeInputHandler::setServiceId('HelloWorld');
        $response = ServiceFactory::Execute();
        $this->assertNotNull($response);
        $expected = ResultType::Success;
        if (!empty($response->debugInfo->message)) {
            print $response->debugInfo->message."\n";
            print $response->debugInfo->location."\n";
        }
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
    }

}
