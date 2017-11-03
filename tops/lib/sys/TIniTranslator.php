<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 11/3/2017
 * Time: 7:48 AM
 */

namespace Tops\sys;


class TIniTranslator extends TLanguage
{
    private $ini;
    protected function getData()
    {
        if (!isset($this->ini)) {
            $this->ini = @parse_ini_file( __DIR__.'/translations.ini',true);
            if ($this->ini === false) {
                $this->ini = array();
            }
            $configFile = TPath::inConfigPath('translations.ini');
            if ($configFile !== false) {
                $this->importTranslations($configFile);
            }

        }
        return $this->ini;
    }

    private $supportedLanguages;
    public function getSupportedLanguages()
    {
        if (!isset($this->supportedLanguages)) {
            $data = $this->getData();
            $this->supportedLanguages = array_keys($data);
        }
        return $this->supportedLanguages;
    }

    public function importTranslations($iniFilePath)
    {
        $import = @parse_ini_file($iniFilePath,true);
        if (!empty($import)) {
            if (empty($this->ini)) {
                $this->ini = $import;
            } else {
                $this->ini = array_merge_recursive($import,$this->ini);
            }
        }
    }

    private function findTranslation($section,$key) {
        if (empty($section) || empty($key)) {
            return false;
        }
        $result = @$section[$key];
        return empty($result) ? false : $result;
    }

    private $cached = array();

    protected function getTranslation($resourceCode, $defaultText = false) {
        $data = $this->getData();
        $result = false;
        $languages = $this->getLanguages();
        foreach($languages as $language) {
            $section = @$data[$language];
            if (empty($section)) {
                continue;
            }
            // find translation by code
            $result = $this->findTranslation($section,$resourceCode);
            if (empty($result)) {
                // assume literal text
                $result = $this->findTranslation($section,$defaultText);
            }
            if (!empty($result)) {
                break;
            }
        }
        return $result;
    }

    /**
     * @param $resourceCode
     * @param bool $defaultText
     * @return bool|mixed
     */
    public function getText($resourceCode, $defaultText = false)
    {
        $result = @$this->cached[$resourceCode];
        if (empty($result)) {
            $result = $this->getTranslation($resourceCode,$defaultText);
        }
        if (empty($result)) {
            // return default text or resource code
            $result = empty($defaultText) ? $resourceCode : $defaultText;
        }
        $this->cached[$resourceCode] = $result;
        return $result;
    }

    public function setLanguageCode($code = null)
    {
        parent::setLanguageCode($code);
        $this->cached = array();
    }
}