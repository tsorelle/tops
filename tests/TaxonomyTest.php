<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 7/3/2017
 * Time: 10:36 AM
 */

use PHPUnit\Framework\TestCase;

class TaxonomyTest extends TestCase
{
    function testTaxonomyGet() {
        $expected = array('one','two','three');
        $actual = \Tops\sys\TaxonomyManager::GetTerms('test');
        $this->assertEquals(sizeof($expected),sizeof($actual),'Different array sizes');
        foreach ($expected as $term) {
            $exists = array_search($term,$actual) !== false;
            $this->assertTrue($exists,"Term '$term' not found in result");
        }
    }

    function testTaxonomyCreate() {
        $expected = array('apples','oranges','pears');
        \Tops\sys\TaxonomyManager::Create('fruit',$expected);
        $actual = \Tops\sys\TaxonomyManager::GetTerms('fruit');
        $this->assertEquals(sizeof($expected),sizeof($actual),'Different array sizes');
        foreach ($expected as $term) {
            $exists = array_search($term,$actual) !== false;
            $this->assertTrue($exists,"Term '$term' not found in result");
        }
    }

    function testTaxonomyAdd() {
        $expected = array('apples','oranges','pears');
        \Tops\sys\TaxonomyManager::Create('fruit',$expected);
        \Tops\sys\TaxonomyManager::AddTerm('fruit','kiwi');
        $actual = \Tops\sys\TaxonomyManager::GetTerms('fruit');
        $this->assertEquals(4,sizeof($actual),'Different array sizes');
    }

    function testTaxonomyRemove() {
        $expected = array('apples','oranges','pears');
        \Tops\sys\TaxonomyManager::Create('fruit',$expected);
        \Tops\sys\TaxonomyManager::RemoveTerm('fruit','oranges');
        $actual = \Tops\sys\TaxonomyManager::GetTerms('fruit');
        $this->assertEquals(2,sizeof($actual),'Different array sizes');
        $appleIndex = array_search('apples',$actual);
        $this->assertTrue($appleIndex !== false,'No apples');
        $pearIndex = array_search('pears',$actual);
        $this->assertTrue($pearIndex !== false,'No pears');
    }
}
