<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 11/2/2017
 * Time: 5:21 AM
 */

namespace Tops\sys;


abstract class TLanguage
{
    const default = 'en-US';

    public abstract function getSupportedLanguages();
    public abstract function importTranslations($iniFilePath);
    /**
     * @param $resourceCode
     * @param null $defaultText
     * @return bool|string
     */
    public abstract function getText($resourceCode,$defaultText=null);

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
                self::$instance = new TIniTranslator();
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

    public static function getLanguage() {
        try {
            return self::getInstance()->getLanguageCode();
        }
        catch (\Exception $ex) {
            return self::default;
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

    protected function getSiteLanguages() {
        $siteLanguage = TConfiguration::getValue('language','site');
        return $this->parseLanguageCode($siteLanguage);
    }

    private $languages;
    public function getLanguages() {
        if (!isset($this->languages)) {
            $languages = $this->getUserLanguages();
            $languages = array_merge($languages,$this->getSiteLanguages());
            $languages = array_merge($languages,$this->parseLanguageCode(self::default));
            $languages = array_unique($languages);
            $this->languages = $this->filterLanguages($languages);
        }
        return $this->languages;
    }

    public function filterLanguages($languages) {
        $result = array();
        if (!empty($languages)) {
            $count = sizeof($languages);
            $supported = $this->getSupportedLanguages();
            foreach($languages as $language) {
                if (in_array($language, $supported)) {
                    $result[] = $language;
                }
            }
        }
        return $result;
    }

    public function setLanguageCode($code=null) {
        if (!isset($this->languages)) {
            $this->languages = $this->parseLanguageCode(self::default);
        }
        $languages = $this->parseLanguageCode($code);
        if (!empty($languages)) {
            $languages = array_unique(array_merge($languages,$this->languages));
            $this->languages = $this->filterLanguages($languages);
        }
    }

    protected function getUserLanguages()
    {
        $accept = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ?
            $_SERVER['HTTP_ACCEPT_LANGUAGE'] : self::default;
        return explode(',', explode(';', $accept)[0]);
    }


    protected function parseLanguageCode($code=self::default) {
        if (empty($code)) {
            return array();
        }
        $result = array($code);
        $parts = explode('-',$code);
        if (sizeof($parts)>1) {
            $result[] = $parts[0];
        }
        return $result;
    }


}