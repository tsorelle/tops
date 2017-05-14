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
    public function testNormalize() {
        $testpath = __DIR__;
        $actual = TPath::normalize($testpath);
        $this->assertNotNull($actual);
        print"actual normalized: $actual\n";
    }

    public function testFileRoot() {
        TPath::clearFileRoot();
        $actual = TPath::getFileRoot();
        $this->assertNotNull($actual);
        print"actual file root: $actual\n";
    }
    public function testFileRootWithOffset() {
        TPath::clearFileRoot();
        $actual = TPath::getFileRoot(1);
        $this->assertNotNull($actual);
        print"actual with offset: $actual\n";
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
}
