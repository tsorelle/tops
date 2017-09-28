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
    public function testNameFormat() {
        $key = 'peanut_administrator';
        $wordcap = 'Peanut Administrator';
        $initialcap = 'Peanut administrator';

        $actual = TStrings::convertNameFormat($key,TStrings::initialCapFormat);
        $expected = $initialcap;
        $this->assertEquals($expected,$actual);

        $actual = TStrings::convertNameFormat($key,TStrings::wordCapsFormat);
        $expected = $wordcap;
        $this->assertEquals($expected,$actual);

        $actual = TStrings::convertNameFormat($wordcap,TStrings::keyFormat);
        $expected = $key;
        $this->assertEquals($expected,$actual);

        $actual = TStrings::convertNameFormat($wordcap,TStrings::initialCapFormat);
        $expected = $initialcap;
        $this->assertEquals($expected,$actual);

        $actual = TStrings::convertNameFormat($initialcap,TStrings::keyFormat);
        $expected = $key;
        $this->assertEquals($expected,$actual);

        $actual = TStrings::convertNameFormat($initialcap,TStrings::wordCapsFormat);
        $expected = $wordcap;
        $this->assertEquals($expected,$actual);

        $actual = TStrings::convertNameFormat($initialcap,TStrings::initialCapFormat);
        $expected = $initialcap;
        $this->assertEquals($expected,$actual);

        $actual = TStrings::convertNameFormat($wordcap,TStrings::wordCapsFormat);
        $expected = $wordcap;
        $this->assertEquals($expected,$actual);

        $actual = TStrings::convertNameFormat($key,TStrings::keyFormat);
        $expected = $key;
        $this->assertEquals($expected,$actual);


        $key = 'my_favorite_year';
        $wordcap = 'My Favorite Year';
        $initialcap = 'My favorite year';

        $actual = TStrings::convertNameFormat($key,TStrings::initialCapFormat);
        $expected = $initialcap;
        $this->assertEquals($expected,$actual);

        $actual = TStrings::convertNameFormat($key,TStrings::wordCapsFormat);
        $expected = $wordcap;
        $this->assertEquals($expected,$actual);

        $actual = TStrings::convertNameFormat($wordcap,TStrings::keyFormat);
        $expected = $key;
        $this->assertEquals($expected,$actual);

        $actual = TStrings::convertNameFormat($wordcap,TStrings::initialCapFormat);
        $expected = $initialcap;
        $this->assertEquals($expected,$actual);

        $actual = TStrings::convertNameFormat($initialcap,TStrings::keyFormat);
        $expected = $key;
        $this->assertEquals($expected,$actual);

        $actual = TStrings::convertNameFormat($initialcap,TStrings::wordCapsFormat);
        $expected = $wordcap;
        $this->assertEquals($expected,$actual);

        $actual = TStrings::convertNameFormat($initialcap,TStrings::initialCapFormat);
        $expected = $initialcap;
        $this->assertEquals($expected,$actual);

        $actual = TStrings::convertNameFormat($wordcap,TStrings::wordCapsFormat);
        $expected = $wordcap;
        $this->assertEquals($expected,$actual);

        $actual = TStrings::convertNameFormat($key,TStrings::keyFormat);
        $expected = $key;
        $this->assertEquals($expected,$actual);

        $actual = TStrings::convertNameFormat($initialcap,TStrings::wordCapsFormat);
        $expected = $wordcap;
        $this->assertEquals($expected,$actual);



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
}
