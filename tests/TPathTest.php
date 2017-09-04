<?php
/**
 * Created by PhpStorm.
 * User: terry
 * Date: 5/14/2017
 * Time: 7:32 AM
 */

use Tops\sys\TPath;
use PHPUnit\Framework\TestCase;

class TPathTest extends TestCase
{
    public function tearDown() {
        //  these tests fool with default ini location, set by inittesting.php.
        //  restore to tests/config.
        $projectFileRoot =   str_replace('\\','/', realpath(__DIR__.'/..')).'/';
        \Tops\sys\TPath::Initialize($projectFileRoot,'tests/config');
    }
    public function testNormalize() {
        $testpath = __DIR__;
        $actual = TPath::normalize($testpath);
        $this->assertNotNull($actual);
        print"normalized current path: $actual\n";
    }

    public function testFileRoot() {
        TPath::clearCache();
        $actual = TPath::getFileRoot();
        $this->assertNotNull($actual);
        print"file root: $actual\n";
    }
    public function testConfigPath() {
        TPath::clearCache();
        $actual = TPath::getConfigPath();
        $this->assertNotNull($actual);
        print"config path: $actual\n";
    }

    public function testFileRootWithOffset() {
        TPath::clearCache();
        $actual = TPath::getFileRoot(1);
        $this->assertNotNull($actual);
        print"file root with offset = 1: $actual\n";
    }
    public function testCombine() {
        $p1 = 'one\\two\\three\\';
        $p2 = '/four/five/six';
        $expected = 'one/two/three/four/five/six';
        $actual = TPath::combine($p1,$p2,false);
        $this->assertEquals($expected,$actual,'testCombine failed.');
    }
    public function testCombineAndNormalize() {
        $expected = TPath::stripDriveLetter(__DIR__);
        $p1 = TPath::stripDriveLetter(__DIR__.'/..').'/';
        $p2 = '\\tests';
        $actual = TPath::combine($p1,$p2);
        $this->assertEquals($expected,$actual,'testCombine normalize failed.');
    }

    public function testFromFileRoot() {
        $fileRoot =   substr(str_replace('\\','/', realpath(__DIR__.'/..')),2);
        $path = 'tests/config/settings.ini';
        $expected = "$fileRoot/$path";
        $actual = TPath::fromFileRoot($path);
        $this->assertEquals($expected,$actual);
    }
}
