<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 11/2/2017
 * Time: 5:21 AM
 */

namespace Tops\sys;


class TLanguage
{
    /**
     * @var TLanguage
     */
    private static $instance;

    private static function getInstance()
    {
        if (!isset(self::$instance)) {
            if (TObjectContainer::HasDefinition('tops.language')) {
                self::$instance = TObjectContainer::Get('tops.language');
            } else {
                self::$instance = new TLanguage();
            }
        }
        return self::$instance;

    }

    /**
     * Translate text or resource code
     *
     * @param $resourceCode
     * @param null $defaultText
     * @return bool|string
     *
     * Note: as a practice, translations are only user for 'user readable text'
     * exception messages and log entries should be in literal us english
     */
    public static function text($resourceCode,$defaultText=null) {
        try {
            return self::getInstance()->getText($resourceCode, $defaultText);
        }
        catch (\Exception $ex) {
            return $defaultText === null ? $resourceCode : $defaultText;
        }
    }

    public static function getLanguageCode() {
        try {
            return self::getInstance()->languageCode;
        }
        catch (\Exception $ex) {
            return 'en-us';
        }
    }

    /**
     * Translate an array of resourceCode => defaultText
     *
     * @param $items  array
     * @return array
     */
    public static function getTranslations($items) {
        $result = array();
        foreach ($items as $code => $default) {
            $result[$code] = self::text($code,$default);
        }

        return $result;
    }

    protected $languageCode;
    protected $initialized = false;

    public function __construct()
    {
        $this->setLanguageCode(TConfiguration::getValue('language','site','en-us'));
    }

    public function setLanguageCode($code)
    {
        if ($this->languageCode != $code) {
            $this->initialized = false;
        }
    }

    /**
     * @param $resourceCode
     * @param null $defaultText
     * @return bool|string
     */
    public function getText($resourceCode,$defaultText=null) {
        if (!$this->initialized) {
            $this->initialize();
        }
        $text = $this->lookup($resourceCode,$defaultText);
        if ($text === false) {
            return empty($defaultText) ? $resourceCode : $defaultText;
        }
        return $text;
    }

    /**
     * @param $resourceCode
     * @return bool|string
     */
    protected function lookup($resourceCode, $defaultText=null) {
        // override in subclass for language translation implementation
        return false;
    }

    protected function initialize() {
        // override in subclass as needed
        $this->initialized = true;
    }
}