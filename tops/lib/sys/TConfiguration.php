<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 1/25/2017
 * Time: 6:27 AM
 */

namespace Tops\sys;


/**
 * Class AftmConfiguration
 * @package Tops
 *
 * Handles access to configuration file config.ini, located in same directory as this class file.
 * For ini file documentation see http://php.net/manual/en/function.parse-ini-file.php
 *
 */
class TConfiguration
{
    /**
     * @var array
     */
    private static $ini = null;
    private static $isValid = true;
    private static $requireFiles = false;
    private static $throwExceptions = true;

    // private static $configs = array();

    public static function clearCache()
    {
        self::$ini = null;
        self::$isValid = true;
        self::$requireFiles = false;
        self::$throwExceptions = true;
    }

    public static function reset()
    {
        self::$ini = null;
        self::$isValid = true;
        self::$requireFiles = false;
        self::$throwExceptions = true;

    }

    public static function requireFiles($value = true)
    {
        self::$requireFiles = $value;
    }

    public static function throwExceptions($value = true)
    {
        self::$throwExceptions = $value;
    }

    private static function getIni(array $packages = null)
    {
        if (self::$ini === null) {
            self::$isValid = true;
            self::$ini = self::loadIni();
        }
        // self::$configs = array();
        return self::$ini;
    }

    private static function addError(array $ini = array(), $message)
    {
        if (!isset($ini['errors'])) {
            $ini['errors'] = array();
        }


    }

    private static function loadIni($fileName = 'settings.ini', $iniPath = null)
    {

        if ($iniPath === null) {
            $iniPath = TPath::getConfigPath() . $fileName;
        } else {
            $iniPath = TPath::combine($iniPath, $fileName);
        }
        if (file_exists($iniPath)) {
            $result = @parse_ini_file($iniPath, true);
            if ($result === false) {
                self::$isValid = false;
                $errMsg = 'Fatal error: ' . (isset($php_errormsg) ? trim($php_errormsg) : 'unknown');
                if (self::$throwExceptions) {
                    throw new \Exception($errMsg);
                }
                return array('errors' => array($fileName => $errMsg));
            }
            return $result;
        } else {
            $errMsg = "File not found: $iniPath";
            if (self::$requireFiles) {
                throw new \Exception("Ini error: $errMsg");
            }
            return array('errors' => array($fileName => "File not found: $iniPath"));
        }
    }

    public static function loadAppSettings($files = 'settings.ini')
    {
        if (self::$ini == null) {
            self::$ini = array();
        }
        $files = explode(',', $files);
        foreach ($files as $fileName) {
            self::addSettings($fileName);
        }
    }

    public static function addSettings($fileName, $iniPath = null, $replaceExisting = true)
    {
        $ini = self::loadIni($fileName, $iniPath);
        if ($ini !== false) {
            $sections = array_keys($ini);
            foreach ($sections as $section) {
                if (!array_key_exists($section, self::$ini)) {
                    self::$ini[$section] = array();
                }
                foreach ($ini[$section] as $key => $value) {
                    if ($replaceExisting || !array_key_exists($key, self::$ini)) {
                        self::$ini[$section][$key] = $value;
                    }
                }
            }
        }
    }

    public static function getErrors()
    {
        if (array_key_exists('errors', self::$ini)) {
            return self::$ini['errors'];
        }
        return array();
    }

    public static function hasErrors()
    {
        return !empty(self::$ini['errors']);
    }

    public static function getFatalErrors()
    {
        $result = array();
        $errors = self::getErrors();

        foreach ($errors as $key => $error) {
            if (substr($error, 0, 12) === 'Fatal error:') {
                $result[$key] = $error;
            }
        }
        return $result;
    }

    public static function isValid()
    {
        return self::$isValid;
    }


    /**
     * Retrieve value by key and section
     *
     * @param $key
     * @param $sectionKey
     * @param bool $defaultValue - returned if value does not exist
     * @return bool|mixed
     */
    public static function getValue($key, $sectionKey, $defaultValue = false)
    {
        $section = self::getIniSection($sectionKey);
        if (is_array($section) && array_key_exists($key, $section)) {
            return $section[$key];
        }
        return $defaultValue;
    }

    public static function getBoolean($key, $sectionKey, $defaultValue = false)
    {
        $section = self::getIniSection($sectionKey);
        if (is_array($section) && array_key_exists($key, $section)) {
            return !empty($section[$key]);
        }
        return $defaultValue;
    }

    /**
     * Get array of values by section key
     *
     * @param $sectionKey
     * @return bool|mixed
     */
    public static function getIniSection($sectionKey)
    {
        $ini = self::getIni();
        if (array_key_exists($sectionKey, $ini)) {
            return $ini[$sectionKey];
        }
        return false;
    }


    public function getIniEmailValues($key, $sectionKey)
    {
        $result = array();
        $keys = self::getValue($key, $sectionKey);
        if ($keys !== false) {
            $keys = explode(',', $keys);
            foreach ($keys as $key) {
                $key = trim($key);
                if (strstr($key, '@')) {
                    $email = $key;
                } else {
                    $email = self::getValue($key, 'email');
                }
                if (!empty($email)) {
                    $result[] = $email;
                }
            }
        }
        return $result;
    }

    public function getSmtpSettings()
    {
        $result = new \stdClass();
        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $result->debug = self::getValue('debug', 'mail', 0);

        //Set the hostname of the mail server
        $result->host = self::getValue('host', 'mail', 'localhost');
        //Set the SMTP port number - likely to be 25, 465 or 587
        $result->port = self::getValue('port', 'mail', 25);
        //Whether to use SMTP authentication
        $auth = self::getBoolean('auth', 'mail');
        if ($auth) {
            $result->auth = true;
            //Username to use for SMTP authentication
            $result->username = self::getValue('username', 'mail', '');
            //Password to use for SMTP authentication
            $result->password = self::getValue('password', 'mail', '');
        }
        return $result;

    }
}