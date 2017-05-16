<?php
/**
 * Created by PhpStorm.
 * User: terry
 * Date: 5/16/2017
 * Time: 5:52 AM
 */

namespace Tops\services;


class DefaultInputHandler extends ServiceRequestInputHandler
{

    /**
     * @return 'POST' | 'GET'
     */
    protected function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @return mixed
     */
    public function get(string $key)
    {
        if ($this->getMethod() == 'POST') {
            return $_POST[$key];
        }
        return $_GET[$key];
    }

    public function getSecurityToken()
    {
        return empty($_GET[ServiceRequestInputHandler::securityTokenKey]) ? null
            : $_GET[ServiceRequestInputHandler::securityTokenKey];
    }

    public function getServiceNamespace($key)
    {
        return $key; //assume literal namespace
    }

}