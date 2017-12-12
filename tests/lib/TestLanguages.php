<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 12/12/2017
 * Time: 7:57 AM
 */

namespace TwoQuakers\testing;


use Tops\sys\TLanguage;

class TestLanguages extends TLanguage
{

    public function getSupportedLanguages()
    {
        return ['en-US','en','sp-MX','sp','en-UK'];
    }

    /**
     * @param $iniFilePath
     * @param string $username
     * @return int number imported
     */
    public function importTranslations($iniFilePath, $username = 'admin')
    {
        return 1; // implement if needed by tests
    }

    /**
     * @param $resourceCode
     * @param null $defaultText
     * @return bool|string
     */
    protected function getTranslation($resourceCode, $defaultText = false)
    {
        return 'translated'; // implement if needed by tests
    }
}