<?php
/**
 * Created by PhpStorm.
 * User: terry
 * Date: 5/14/2017
 * Time: 6:51 AM
 */

namespace Tops\sys;


class TPath
{
    private static $fileRoot = null;
    private static $configPath = null;
    public static function getConfigPath() {
        if (self::$configPath === null) {
            self::getPaths();
        }
        return self::$configPath;
    }

    /**
     * Return cleaned and verified path to document root or offset.
     */
    public static function getFileRoot($offset = false)
    {

        if (self::$fileRoot === null) {
            self::getPaths($offset);
        }
        return self::$fileRoot;
    }

    private static function getPaths($offset = false)
    {
        $ini = parse_ini_file(__DIR__.'/settings.ini');
        if ($offset === false) {
            $offset = ($ini === false || empty($ini['root-level'])) ? 3 : $ini['root-level'];
        }
        $configLocation = ($ini === false || empty($ini['app-config-location'])) ? 'application/config' : $ini['app-config-location'];
        $path = __DIR__;
        for($i = 0; $i < $offset; $i++) {
            $path .= '\..';
        }
        self::$fileRoot = self::normalize($path).'/';
        self::$configPath = self::$fileRoot.self::fixSlashes($configLocation).'/';
    }

    public static function clearCache() {
        self::$configPath = null;
        self::$fileRoot = null;
    }

    public static function stripDriveLetter($path)
    {
        $path = str_replace('\\','/',$path);
        $p = strpos($path,':');
        if ($p === 1) {
            return  strlen($path) < 3 ? '' : substr($path,2);
        }
        return $path;

    }
    public static function fixSlashes($path) {
        $path = str_replace('\\','/',$path);
        while(strpos($path,'//') !== false) {
            $path = str_replace('//','/',$path);
        }
        return trim($path);
    }

    public static function normalize($path)
    {
        $path = self::fixSlashes($path);

        $real = realpath($path);
        if ($real === false) {
            throw new \Exception("Path not found: '$path'");
        }
        return self::stripDriveLetter($real) ;
    }

    public static function combine($path1,$path2,$normalize=true) {
        $combined = "$path1/$path2";
        if ($normalize) {
            return self::normalize("$path1/$path2");
        }
        return self::fixSlashes($combined);
    }
}