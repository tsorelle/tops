<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 12/29/2016
 * Time: 7:06 AM
 */
/**
 * Must declare route in \bootstrap\app.php
 *
 * Route::register(
 *      '/tops/service/execute/{arg}',
 *      'Concrete\Package\Tops\Controller\ServiceRequestHandler::executeService'
 *      );
 *
 */
namespace Tops\services;

use Tops\sys;
use Tops\services\ServiceRequestInputHandler;


class ServiceFactory
{

    /**
     * @var ServiceRequestInputHandler
     */
    private $inputHandler;
    
    private static $instance;
    public static function Execute() {
        if (!isset(self::$instance)) {
            self::$instance = new ServiceFactory();
        }
        self::$instance->executeService();
    }

    public function __construct()
    {
        $inputHandler = sys\TObjectContainer::Get('request-input-handler');
        $this->inputHandler = $inputHandler === false ?  new DefaultInputHandler() : $inputHandler;
    }

    private function getSecurityToken(ServiceRequestInputHandler $request) {

        $tokensEnabled = sys\TConfiguration::getValue('xxstokens','security',true);
        if ($request != null && $tokensEnabled) {
            $securityToken = $request->get('topsSecurityToken');
            if (!$securityToken) {
                return 'invalid';
            }
        }
        return '';
    }

    public function executeService() {
        $response = '';
        try {

            $serviceId = $this->inputHandler->getServiceId();

            if ($serviceId == 'getxsstoken') {
                sys\TSession::Initialize();
                return;
            }

            $securityToken = $this->inputHandler->getSecurityToken();
            $input = $this->inputHandler->getInput();

            $parts = explode('::', $serviceId);
            if (sizeof($parts) == 1) {
                $namespace =  sys\TConfiguration::getValue('applicationNamespace', 'services');
            } else {
                $namespace = $this->inputHandler->getServiceNamespace($parts[0]);
                $serviceId =  $parts[1];
            }

            $className = $namespace . "\\" . $serviceId . 'Command';

            if (!class_exists($className)) {
                throw new \Exception("Cannot instatiate service '$className'.");
            }

            /**
             * @var $cmd TServiceCommand
             */
            $cmd = new $className();
            $response = $cmd->execute($input,$securityToken);

        }
        catch (\Exception $ex) {
            // todo: exception logging
            /*
            $rethrow = $instance->handleException($ex);
            if ($rethrow) {
                throw $ex;
            }
            */
            $debugInfo = new \stdClass();
            $debugInfo->message = $ex->getMessage();
            $debugInfo->location = $ex->getFile().": Line ".$ex->getLine();
            $debugInfo->trace = $ex->getTraceAsString();

            $response = $this->getFailureResponse($debugInfo);
        }

        echo json_encode($response);
    }

    /**
     * @return TServiceResponse
     */
    private function getFailureResponse($debugInfo = null) {
        if (!isset($this->failureResponse)) {
            $this->failureResponse = new TServiceErrorResponse();
            $this->failureResponse->Result = ResultType::ServiceFailure;
            $message = new TServiceMessage();
            $message->MessageType = MessageType::Error;
            $message->Text = 'Service failed. If the problem persists contact the site administrator.';
            $this->failureResponse->Messages = array($message);
            $this->failureResponse->debugInfo = $debugInfo;
        }
        return $this->failureResponse;
    }

}