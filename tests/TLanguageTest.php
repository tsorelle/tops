<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 12/12/2017
 * Time: 6:36 AM
 */

use Tops\sys\TLanguage;
use PHPUnit\Framework\TestCase;

class TLanguageTest extends TestCase
{

    protected function setUp()
    {
        TLanguage::setInstance(new \TwoQuakers\testing\TestLanguages());
    }

    protected function tearDown()
    {
        TLanguage::clearInstance();
    }

    private function getLanguageCodes()
    {
        $elements = TLanguage::getLanguageCodes();
        $result = '[';
        if (!empty($elements)) {
            $result .= join(',',$elements);
        }
        return $result.']';
    }

    public function testGetLanguageCodes() {
        $actual = $this->getLanguageCodes();
        $this->assertNotEmpty($actual);
        $this->assertEquals('[en-US,en]',$actual);
    }

    public function testSetLanguages() {
        $default = 'en-US,en';
        $expected = "[$default]";
        $actual = $this->getLanguageCodes();
        $this->assertEquals($expected,$actual);

        // french not supported
        TLanguage::setUserLanguages('fr,fr-FR');
        $expected = "[$default]";
        $actual = $this->getLanguageCodes();
        $this->assertEquals($expected,$actual);

        // french not supported but spanish is
        TLanguage::setUserLanguages('fr,sp-MX');
        $expected = "[sp-MX,sp,$default]";
        $actual = $this->getLanguageCodes();
        $this->assertEquals($expected,$actual);

        TLanguage::setUserLanguages('sp-MX');
        $expected = "[sp-MX,sp,$default]";
        $actual = $this->getLanguageCodes();
        $this->assertEquals($expected,$actual);

    }

    public function testFindFile() {
        $path = 'files';
        $filename = 'expected.ini';

        $expected = realpath("$path/en-us/$filename");
        $actual = TLanguage::FindLangugeFile($path,$filename);
        $this->assertEquals($expected,$actual);

        TLanguage::setUserLanguages('sp-MX');
        $expected = realpath("$path/sp-mx/$filename");
        $actual = TLanguage::FindLangugeFile($path,$filename);
        $this->assertEquals($expected,$actual);

        TLanguage::setUserLanguages('sp-sp');
        $expected = realpath("$path/sp/$filename");
        $actual = TLanguage::FindLangugeFile($path,$filename);
        $this->assertEquals($expected,$actual);

        TLanguage::setUserLanguages('fr');
        $expected = realpath("$path/en-us/$filename");
        $actual = TLanguage::FindLangugeFile($path,$filename);
        $this->assertEquals($expected,$actual);

        TLanguage::setUserLanguages('fr,sp-MX');
        $expected = realpath("$path/sp-mx/$filename");
        $actual = TLanguage::FindLangugeFile($path,$filename);
        $this->assertEquals($expected,$actual);



    }
}
