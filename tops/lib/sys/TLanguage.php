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

    public static function text($resourceCode,$defaultText=null) {
        try {
            if (!isset(self::$instance)) {
                if (TObjectContainer::HasDefinition('tops.language')) {
                    self::$instance = TObjectContainer::Get('tops.language');
                } else {
                    self::$instance = new TLanguage();
                }
            }
            return self::$instance->getText($resourceCode, $defaultText);
        }
        catch (\Exception $ex) {
            return $defaultText === null ? $resourceCode : $defaultText;
        }
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

    public function getText($resourceCode,$defaultText=null) {
        if (!$this->initialized) {
            $this->initialize();
        }
        $text = $this->lookup($resourceCode);
        if ($text === false) {
            return $defaultText === null ? $resourceCode : $defaultText;
        }
        return $text;
    }

    protected function lookup($resourceCode) {
        // override in subclass for language translation implementation
        return false;
    }

    protected function initialize() {
        // override in subclass as needed
        $this->initialized = true;
    }
}