<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 9/4/2017
 * Time: 6:51 AM
 */

namespace Tops\sys;


class TWebSite
{
    private static $baseUrl;
    public static function GetSiteUrl() {
        if (!isset($_SERVER['HTTP_HOST'])) {
            return '';
        }
        if(isset($_SERVER['HTTPS'])){
            $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
        }
        else{
            $protocol = 'http';
        }
        return $protocol . "://" . $_SERVER['HTTP_HOST'];
    }

    public static function GetBaseUrl(){
        if (isset(self::$baseUrl)) {
            return self::$baseUrl;
        }
        self::$baseUrl = TConfiguration::getValue('url','site');
        if (empty(self::$baseUrl))  {
            self::$baseUrl = self::GetSiteUrl();
        }
        return self::$baseUrl;
    }

    public static function SetBaseUrl($value) {
        $baseUrl = $value;
    }
}