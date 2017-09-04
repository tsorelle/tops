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
    const dont_normalize = false;
    const normalize_with_exception = 1;
    const normalize_no_exception = 2;

    private static $fileRoot = null;
    private static $configPath = null;
    public static function getConfigPath() {
        if (self::$configPath === null) {
            self::getPaths();
        }
        return self::$configPath;
    }

    public static function Initialize($projectRoot,$configLocation = 'application/config') {
        self::$fileRoot = self::normalize($projectRoot).'/';
        self::$configPath = self::$fileRoot.self::fixSlashes($configLocation).'/';
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
        if (empty(self::$fileRoot)) {
            if ($offset === false) {
                $path = $_SERVER['DOCUMENT_ROOT'];
            }
            else {
                $path = __DIR__;
                for($i = 0; $i < $offset; $i++) {
                    $path .= '\..';
                }
            }
            self::$fileRoot = self::normalize($path).'/';
        }
        if (empty(self::$configPath)) {
            $configLocation = 'application/config';
            self::$configPath = self::$fileRoot.self::fixSlashes($configLocation).'/';
        }

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

    public static function normalize($path,$throwException=true)
    {
        $path = self::fixSlashes($path);

        $real = realpath($path);
        if ($real === false) {
            if ($throwException) {
                throw new \Exception("Path not found: '$path'");
            }
            return false;
        }
        return self::stripDriveLetter($real) ;
    }

    public static function combine($path1,$path2,$mode=self::normalize_with_exception) {
        $combined = "$path1/$path2";
        if ($mode===self::dont_normalize) {
            return self::fixSlashes($combined);
        }
        return self::normalize("$path1/$path2");
    }

    public static function fromFileRoot($path,$normalize=false) {
        $root = self::getFileRoot();
        return self::combine($root,$path,$normalize);
    }
}