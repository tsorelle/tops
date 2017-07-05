<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 7/5/2017
 * Time: 5:58 AM
 */

use PHPUnit\Framework\TestCase;
use Tops\ui\TViewModelManager;

class ViewModelManagerTest extends TestCase
{
    function testGetViewModelSettings()
    {
        // reinitialize test path, maybe changed by previous test
        $projectFileRoot =   str_replace('\\','/', realpath(__DIR__.'/..')).'/';
        \Tops\sys\TPath::Initialize($projectFileRoot,'tests/config');

        $actual = TViewModelManager::getViewModelSettings('testpage');
        $this->assertNotEmpty($actual);
        $expected = 'application/mvvm/view/TestPage.html';
        $this->assertEquals($expected,$actual->view);

        $actual = TViewModelManager::getViewModelSettings('qnut/test');
        $this->assertNotEmpty($actual);
        $expected = 'tests/pnut/packages/test-package/view/QnutTest.html';
        $this->assertEquals($expected,$actual->view);

    }

    function testGetPackageList() {
        // reinitialize test path, maybe changed by previous test
        $projectFileRoot =   str_replace('\\','/', realpath(__DIR__.'/..')).'/';
        \Tops\sys\TPath::Initialize($projectFileRoot,'tests/config');

        $actual = TViewModelManager::getPackageList();
        $this->assertNotEmpty($actual);
        $expected = 2;
        $this->assertEquals($expected, sizeof($actual),'Wrong package count.');

    }
}
