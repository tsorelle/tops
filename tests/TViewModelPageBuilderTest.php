<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 8/21/2017
 * Time: 8:31 AM
 */

use Tops\ui\TViewModelPageBuilder;
use PHPUnit\Framework\TestCase;

class TViewModelPageBuilderTest extends TestCase
{
    public function testBuildPageContent() {
        $expected = 'theme:cerulean; view: view content here; vmname: testViewModel';
        $template = 'theme:[[theme]]; view: [[view]]; vmname: [[vmname]]';
        $settings = new \Tops\ui\TViewModelInfo();
        $settings->vmName='testViewModel';
        $settings->view=__DIR__.'/files/testview1.html';
        $builder = new TViewModelPageBuilder();
        $actual = $builder->buildPageContent($settings,$template);
        $this->assertEquals($expected,$actual);
    }

    public function testBuildPage() {
        $expected = 'theme:cerulean; view: view content here; vmname: testViewModel';
        $templatePath = __DIR__.'/files';
        $settings = new \Tops\ui\TViewModelInfo();
        $settings->vmName='testViewModel';
        $settings->view=__DIR__.'/files/testview1.html';
        $builder = new TViewModelPageBuilder();
        $actual = $builder->buildPage($settings,$templatePath);
        $this->assertEquals($expected,$actual);

    }
}
