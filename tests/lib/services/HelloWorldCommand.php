<?php
/**
 * Created by PhpStorm.
 * User: terry
 * Date: 5/15/2017
 * Time: 5:02 PM
 */

namespace TwoQuakers\testing\services;


class HelloWorldCommand extends \Tops\services\TServiceCommand
{
    protected function run()
    {
        $this->addInfoMessage('Hello World',true);
        $responseValue = new \stdClass();
        $responseValue->message = "Greatings earthlings.";
        $this->setReturnValue($responseValue);
    }
}