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

    public static function inConfigPath($filename,$mode=self::normalize_no_exception) {
        $root = self::getConfigPath();
        return self::combine($root,$filename,$mode);
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
        return self::normalize("$path1/$path2",($mode === self::normalize_with_exception));
    }

    public static function joinPath($path1,$path2) {
        return self::combine($path1,$path2,self::dont_normalize);
    }

    public static function fromFileRoot($path,$normalize=false) {
        $root = self::getFileRoot();
        return self::combine($root,$path,$normalize);
    }

    public static function incrementFileName($baseDir,$fileName,$prefix='copy',$stampFirst=null)
    {
        if ($stampFirst == null) {
            $stampFirst = $prefix !== 'copy';
        }

        if (substr($baseDir,-1) == '/') {
            $baseDir = substr($baseDir,0,strlen($baseDir -1));
        }
        $filePath = self::joinPath($baseDir,$fileName);

        if ((!$stampFirst) && (!file_exists($filePath))) {
            return $fileName;
        }
        $name = pathinfo($fileName, PATHINFO_FILENAME);
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);

        $parts = explode('-', $name);
        $last = array_pop($parts);
        if (is_numeric($last) && $last > 0 && $last == round($last, 0)) {
            $last = array_pop($parts);
        }
        if ($last === $prefix) {
            $name = implode('-', $parts);
        }

        $search = $baseDir . '/' . $name . '-' . $prefix . '-*.' . $ext;
        $files = glob($search);
        if (count($files) == 0) {
            if ($stampFirst && file_exists($filePath)) {
                return $name . '-' . $prefix . '-0002.' . $ext;
            }
            return $name . '-' . $prefix . '-0001.' . $ext;
        }
        sort($files);
        $i = 0;
        while (count($files)) {
            $current = pathinfo(array_pop($files), PATHINFO_FILENAME);
            $parts = explode('-', $current);
            $last = array_pop($parts);
            if ((is_numeric($last) && $last > 0 && $last == round($last, 0))) {
                $i = intval($last);
                break;
            }
        }

        if ($i === 9999) {
            return FALSE;
        }
        return $name . '-' . $prefix . '-' . sprintf('%04d', ++$i) . '.' . $ext;
    }

    /**
     * @param $fileName
     * @return string
     *
     * Enforces a naming standard where all word seperators are dashes and all characters are lowercase.
     * Avoids visual confusion caused by spaces or underscores and case sensitivity conflicts on unix-like systems
     *
     */
    public static function normalizeFileName($fileName)
    {
        $fileName = str_replace([' ', '_'], '-', strtolower(trim($fileName)));
        while (true) {
            $result = str_replace('--', '-', strtolower($fileName));
            if ($fileName == $result) {
                return $result;
            }
            $fileName = $result;
        }
        return $fileName;
    }


    /**
     * @param $filePath
     * @return string
     *
     * See normalizeFileName for naming rules and rationale
     * Normalizes entire path, switches windows specific path seperators.
     */
    public static function normalizeFilePath($filePath)
    {
        $filePath = trim($filePath);
        if (empty($filePath)) {
            return '';
        }
        $result = [];
        $filePath = str_replace('\\','/',$filePath);
        $parts = explode('/', $filePath);
        foreach ($parts as $part) {
            $result[] = self::normalizeFileName($part);
        }
        return implode('/', $result);
    }

}