<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 1/15/2018
 * Time: 10:50 AM
 */

namespace Tops\services;


use Tops\sys\TConfiguration;
use Tops\sys\TObjectContainer;

class DownloadServiceFactory
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
        return self::$instance->executeService();
    }

    public function __construct()
    {
        $inputHandler = TObjectContainer::Get('services.inputhandler');
        $this->inputHandler = $inputHandler === false ?  new DefaultInputHandler() : $inputHandler;
    }

    /**
     * @return string|TServiceResponse
     * @throws \Exception
     */
    public function executeService()
    {
        $serviceId = $this->inputHandler->getServiceId();
        $securityToken = $this->inputHandler->getSecurityToken();
        $input = $this->inputHandler->getValues(['serviceCode','sid']);
        $parts = explode('::', $serviceId);
        if (sizeof($parts) == 1) {
            $namespace = TConfiguration::getValue('applicationNamespace', 'services');
            if (empty($namespace)) {
                throw new \Exception('For default service, "applicationNamespace=" is required in settings.ini');
            }
            $namespace .= "\\" . TConfiguration::getValue('servicesNamespace', 'services', 'services');
        } else {
            $namespace = $this->inputHandler->getServiceNamespace($parts[0]);
            $serviceId = $parts[1];
        }

        // get subdirectories  e.g. where serviceId is 'subdirectory.serviceId'
        $serviceId = str_replace('.', "\\", $serviceId);
        $className = $namespace . "\\" . $serviceId . 'Command';
        if (!class_exists($className)) {
            throw new \Exception("Cannot instatiate service '$className'.");
        }

        /**
         * @var $cmd TServiceCommand
         */
        $cmd = new $className();
        $response = $cmd->execute($input, $securityToken);
        if (is_array($response)) {
            $response = join("\n",$response);
        }
        return $response;
    }




}