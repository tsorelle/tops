<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 9/18/2017
 * Time: 3:39 PM
 */

use Tops\sys\TIniFileMerge;
use PHPUnit\Framework\TestCase;

class TIniFileMergeTest extends TestCase
{
    public function testMerge() {
        $fileDir = __DIR__.'/files';
        $source = "$fileDir/source.ini";
        $target = "$fileDir/target.ini";

        copy("$fileDir/test-file.ini",$target);
        TIniFileMerge::merge($source,$target);

        $expectedIni = parse_ini_file("$fileDir/expected.ini",true);
        $targetIni = @parse_ini_file($target,true);
        $this->assertTrue($targetIni !== false);
        foreach ($expectedIni as $section => $settings ) {
            $this->assertArrayHasKey($section,$targetIni);
            foreach ($settings as $name => $value) {
                $targetSection = $targetIni[$section];
                $this->assertArrayHasKey($name, $targetSection);
                $expected = str_replace("'",'',$value);
                $actual = str_replace("'",'',$targetSection[$name]);
                $this->assertEquals($expected,$actual,"[$section] - $name");
            }
        }

    }
}
