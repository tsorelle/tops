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
    public function tearDown() {
        TWebSite::reset();
    }
    public function testGetBaseUrl() {
        // when offline, this function should return ''
        TWebSite::reset();
        $expected='';
        $actual=TWebSite::GetBaseUrl();
        $this->assertEquals($expected,$actual);
    }

    public function testGetBaseUrlFakeServer() {
        TWebSite::reset();
        global $_SERVER;
        $host = 'www.2quakers.net';
        $_SERVER['HTTP_HOST'] = $host;
        $expected="http://$host";;
        $actual=TWebSite::GetBaseUrl();
        $this->assertEquals($expected,$actual);
    }

    public function testExpandUrl() {
        TWebSite::reset();
        global $_SERVER;
        $host = 'www.2quakers.net';
        $subdir = 'files/and/such';
        $_SERVER['HTTP_HOST'] = $host;
        $expected="http://$host/$subdir";
        $actual=TWebSite::ExpandUrl($subdir);
        $this->assertEquals($expected,$actual);
    }

    public function testExpandUrlWithLeadingSlash() {
        TWebSite::reset();
        global $_SERVER;
        $host = 'www.2quakers.net';
        $subdir = '/files/and/such';
        $_SERVER['HTTP_HOST'] = $host;
        $expected="http://$host".$subdir;
        $actual=TWebSite::ExpandUrl($subdir);
        $this->assertEquals($expected,$actual);
    }

    public function testExpandUrlWithBlankUrl() {
        TWebSite::reset();
        global $_SERVER;
        $host = 'www.2quakers.net';
        $subdir = '';
        $_SERVER['HTTP_HOST'] = $host;
        $expected="http://$host";
        $actual=TWebSite::ExpandUrl($subdir);
        $this->assertEquals($expected,$actual);
    }

    public function testGetDomain() {
        TWebSite::reset();
        global $_SERVER;
        $host = 'www.2quakers.net';
        $_SERVER['SERVER_NAME'] = $host;
        $expected = '2quakers.net';
        $actual = TWebSite::GetDomain();
        $this->assertEquals($expected,$actual);

    }
    public function testGetDomainWithSubdomain() {
        TWebSite::reset();
        global $_SERVER;
        $host = 'songs.2quakers.net';
        $_SERVER['SERVER_NAME'] = $host;
        $expected = 'songs.2quakers.net';
        $actual = TWebSite::GetDomain();
        $this->assertEquals($expected,$actual);

    }
}
