<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 9/4/2017
 * Time: 7:04 AM
 */

use Tops\sys\TWebSite;
use PHPUnit\Framework\TestCase;

class TWebSiteTest extends TestCase
{
    public function testGetBaseUrl() {
        // when offline, this function should return ''
        $expected='';
        $actual=TWebSite::GetBaseUrl();
        $this->assertEquals($expected,$actual);
    }

    public function testGetBaseUrlFakeServer() {
        global $_SERVER;
        $host = 'www.2quakers.net';
        $_SERVER['HTTP_HOST'] = $host;
        $expected="http://$host";;
        $actual=TWebSite::GetBaseUrl();
        $this->assertEquals($expected,$actual);
    }
}
