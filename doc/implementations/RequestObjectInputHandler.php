<?php
use Tops\services\ServiceRequestInputHandler;

/**
 * Created by PhpStorm.
 * User: terry
 * Date: 5/16/2017
 * Time: 5:58 AM
 */


// correct and uncomment
// namespace Your\namespace\here;

// Uncomment for Concrete5
// use Concrete\Core\Http\Request;

// Uncomment for Symfony or Drupal 8
// use Symfony\Component\HttpFoundation\Request;

// delete this dummy class when implemented
class Request {
    public static function getInstance() {
        return new Request();
    }
    public function getMethod() {
       return $_SERVER['REQUEST_METHOD'];
    }

    public function get($key)
    {
        if ($this->getMethod() == 'POST') {
            return $_POST[$key];
        }
        return $_GET[$key];

    }
}

class RequestObjectInputHandler extends ServiceRequestInputHandler
{
    /**
     * @return 'POST' | 'GET'
     */
    public function getMethod()
    {
        return Request::getInstance()->getMethod();
    }

    /**
     * @return mixed
     */
    public function get(string $key)
    {
        return Request::getInstance()->get($key);
    }

    public function getSecurityToken()
    {
        return Request::getInstance()->get(ServiceRequestInputHandler::securityTokenKey);
    }

    public function getServiceNamespace($key)
    {
        // if not Concrete5, modify to suit
        // e.g. return $key; //assume literal namespace
        return "\\Concrete\\Package\\$key\\Src\\Services";
    }

}