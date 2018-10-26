<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 10/25/2018
 * Time: 7:31 AM
 */

use Tops\sys\TStringTokenizer;
use PHPUnit\Framework\TestCase;

class TStringTokenizerTest extends TestCase
{

    public function testExtractKeywords()
    {
        $testString = 'Hello terry, this is a test';
        $actual = TStringTokenizer::extractKeywords($testString);
        $this->assertNotEmpty($actual,'No values return');
        $this->assertTrue(is_array($actual));
        $expected = array('hello','terry','this','test');
        $this->assertEquals($expected, $actual);

        $testString = 'Hello terry, "this test" is for phrases';
        $actual = TStringTokenizer::extractKeywords($testString);
        $this->assertNotEmpty($actual,'No values return');
        $this->assertTrue(is_array($actual));
        $expected = array('hello','terry','this test','phrases');
        $this->assertEquals($expected, $actual);

        $testString = 'Hello terry, "this test" is for "phrases"';
        $actual = TStringTokenizer::extractKeywords($testString);
        $this->assertNotEmpty($actual,'No values return');
        $this->assertTrue(is_array($actual));
        $expected = array('hello','terry','this test','phrases');
        $this->assertEquals($expected, $actual);

        $testString = ' "  this test  " is for "phrases "';
        $actual = TStringTokenizer::extractKeywords($testString);
        $this->assertNotEmpty($actual,'No values return');
        $this->assertTrue(is_array($actual));
        $expected = array('this test','phrases');
        $this->assertEquals($expected, $actual);
    }
}
