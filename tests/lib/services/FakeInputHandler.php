<?php
/**
 * Created by PhpStorm.
 * User: terry
 * Date: 5/16/2017
 * Time: 6:25 AM
 */

namespace TwoQuakers\testing\services;


use Tops\services\ServiceRequestInputHandler;

class FakeInputHandler extends \Tops\services\ServiceRequestInputHandler
{
    private static $method = 'POST';
    private static $values = array();

    public static function setMethodPost() {
        self::$method = 'POST';
    }

    public static function setMethodGet() {
        self::$method = 'GET';
    }

    public static function setServiceId($id) {
        if (self::$method == 'POST') {
            self::$values['serviceCode'] = $id;
        } else {
            self::$values['sid'] = $id;
        }
    }

    public static function setInputValue($value) {

        if (self::$method == 'POST') {
            self::$values['request'] = json_encode($value);
        } else {
            self::$values['arg'] = $value;
        }
    }

    /**
     * @return 'POST' | 'GET'
     */
    protected function getMethod()
    {
        return self::$method;
    }

    /**
     * @return mixed
     */
    public function get(string $key)
    {
        return empty(self::$values[$key]) ? '' : self::$values[$key];
    }

    public function getSecurityToken()
    {
        return empty($_GET[ServiceRequestInputHandler::securityTokenKey]) ? null
            : $_GET[ServiceRequestInputHandler::securityTokenKey];
    }

}
