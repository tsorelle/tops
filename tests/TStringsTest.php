<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 7/5/2017
 * Time: 6:43 AM
 */

use PHPUnit\Framework\TestCase;
use \Tops\sys\TStrings;

class TStringsTest extends TestCase
{
    private function runNameFormatTest(array $examples,$uppercase=false) {
        $formatNames = [
            'initialCapFormat',
            'wordCapsFormat',
            'keyFormat',
            'dashedFormat',
            'camelCaseFormat',
            'pascalCaseFormat',
            'wordFormat'
        ];

        for ($i = 0;$i<7; $i++) {
            for ($f = 0; $f<7; $f++) {
                $format = $f+1;
                $subject = $examples[$i];
                $expected = $examples[$f];
                $formatName = $formatNames[$f].($uppercase? ' Uppercase' : '');
                if ($uppercase) {
                    $expected = strtoupper($expected);
                }
                print "$subject to $formatName = '$expected'\n";
                $actual = TStrings::ConvertNameFormat($subject,$format,$uppercase);
                $this->assertEquals($expected,$actual,$formatName);
            }
        }
    }

    public function testNameFormat() {
        $singleWords = [
            'Terry',	// initialCapFormat
            'Terry',    // wordCapsFormat
            'terry',    // keyFormat
            'terry',    // dashedFormat
            'terry',    // camelCaseFormat
            'Terry',    // pascalCaseFormat
            'terry'    // wordFormat
        ];

        $examples = [
            'My favorite year',		// 	initialCapFormat
            'My Favorite Year',    // 	wordCapsFormat
            'my_favorite_year',    // 	keyFormat
            'my-favorite-year',    // 	dashedFormat
            'myFavoriteYear',     // 	camelCaseFormat
            'MyFavoriteYear',      // 	pascalCaseFormat
            'my favorite year'		// 	wordFormat
        ];

        $this->runNameFormatTest($examples);
        $this->runNameFormatTest($singleWords);
        $this->runNameFormatTest($examples,true);
        $this->runNameFormatTest($singleWords,true);
    }

    public function testFormatNamespace() {
        $input = 'simple';
        $actual = \Tops\sys\TStrings::formatNamespace($input);
        $expected = 'Simple';
        $this->assertEquals($expected,$actual);

        $input = 'simple.test';
        $actual = \Tops\sys\TStrings::formatNamespace($input);
        $expected = "Simple\\test";
        $this->assertEquals($expected,$actual);

        $input = 'simple.Test';
        $actual = \Tops\sys\TStrings::formatNamespace($input);
        $expected = "Simple\\Test";
        $this->assertEquals($expected,$actual);

        $input = 'levelone.level-two.my-namespace';
        $actual = \Tops\sys\TStrings::formatNamespace($input);
        $expected = "Levelone\\LevelTwo\\MyNamespace";
        $this->assertEquals($expected,$actual);

        $input = 'two-quakers.testing.services.sub-services';
        $actual = \Tops\sys\TStrings::formatNamespace($input);
        $expected = 'TwoQuakers\\testing\\services\\SubServices';
        $this->assertEquals($expected,$actual);

        $input = 'two-quakers.Testing.services.sub-services';
        $actual = \Tops\sys\TStrings::formatNamespace($input);
        $expected = 'TwoQuakers\\Testing\\services\\SubServices';
        $this->assertEquals($expected,$actual);
    }

    function testToTitle() {
        $expected = "The Wind in the Willows";

        $title = "the wind in the willows";
        $actual = \Tops\sys\TStrings::toTitle($title);
        $this->assertEquals($expected,$actual);

        $title = "the-wind-in-the-willows";
        $actual = \Tops\sys\TStrings::toTitle($title,'-');
        $this->assertEquals($expected,$actual);

    }

    function testCamelCaseExplode() {
        $s = 'ThisIsATest';
        $actual = TStrings::camelCaseExplode($s);
        $this->assertNotEmpty($actual);
        print_r($actual);
    }
}
