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
    private static $baseUrl=null;
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

    public static function ExpandUrl($url) {
        $scheme = strtolower(parse_url($url,4)); // PHP_URL_SCHEME
        if ($scheme=='http' || $scheme =='https:') {
            return $url;
        }
        $base = self::GetBaseUrl();
        if (empty($url)) {
            return $base;
        }
        return strpos($url,'/') === 0 ? $base.$url : "$base/$url";
    }

    public static function GetBaseUrl(){
        if (self::$baseUrl!==null) {
            return self::$baseUrl;
        }
        self::$baseUrl = TConfiguration::getValue('url','site');
        if (empty(self::$baseUrl))  {
            self::$baseUrl = self::GetSiteUrl();
        }
        return self::$baseUrl;
    }

    public static function reset() {
        self::$baseUrl = null;
    }

    public static function SetBaseUrl($value) {
        $baseUrl = $value;
    }
}