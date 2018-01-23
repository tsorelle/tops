<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 1/23/2018
 * Time: 7:22 AM
 */

use Tops\cache\TGlobalCache;
use PHPUnit\Framework\TestCase;

class TGlobalCacheTest extends TestCase
{

    public function testGetCachedItem()
    {
        $expected = 'success';
        $cache = new TGlobalCache();
        $cache->Set('test','success');
        $actual = $cache->Get('test');
        $this->assertEquals($expected,$actual);


    }
}
