<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 7/5/2017
 * Time: 6:43 AM
 */

use PHPUnit\Framework\TestCase;

class TStringsTest extends TestCase
{
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
}
