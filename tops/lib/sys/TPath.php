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
    const docRootOffset = 3;

    public static function clearFileRoot() {
        self::$fileRoot = null;
    }

    /**
     * Return cleaned and verified path to document root or offset.
     */
    public static function getFileRoot($offset = 0)
    {

        if (self::$fileRoot === null) {
            $offset = self::docRootOffset - $offset;
            $path = __DIR__;
            for($i = 0; $i < $offset; $i++) {
                $path .= '\..';
            }
            self::$fileRoot = self::normalize($path);
        }
        return self::$fileRoot;

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
        return $path;

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