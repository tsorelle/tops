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
            $this->importIniTranslations();
        }
        return $this->ini;
    }

    protected function importIniTranslations() {
        // override in subclass to add additional translations
        $this->ini = $this->getCoreTranslations();
        TIniFileMerge::Import(TPath::inConfigPath('translations.ini'),$this->ini);
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

    public function importTranslations($iniFilePath,$username=null)
    {
        TIniFileMerge::Import($iniFilePath,$this->ini);
        return 1;
    }

    private function findTranslation($section,$key) {
        if (empty($section) || empty($key)) {
            return false;
        }
        $result = @$section[$key];
        return empty($result) ? false : $result;
    }

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

}