<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 7/4/2017
 * Time: 10:37 AM
 */

namespace Tops\ui;


use Tops\sys\TConfiguration;
use Tops\sys\TPath;

class TViewModelManager
{
    private static $info;
    private static $vmSettings;
    private static $instance;

    private static $packageList;
    public static function getPackageList() {
        if (!isset(self::$packageList)) {
            $fileRoot = TPath::getFileRoot();
            $peanutRoot = TConfiguration::getValue('peanutRootPath','peanut');
            self::$packageList = array();
            $packagePath = $fileRoot.$peanutRoot."/packages";
            $files = scandir($fileRoot.$peanutRoot."/packages");
            foreach ($files as $file) {
                // package must be a directory containing a package.ini file.
                if ($file != '.' && $file != '..' && file_exists("$packagePath/$file/package.ini")) {
                    self::$packageList[] = $file;
                }
            }
        }
        return self::$packageList;
    }


    private static function expandLocationPath($path) {
        if (empty($path)) {
            return TConfiguration::getValue('mvvmPath','peanut','');
        }
        else if (substr($path,0,1) === '/') {
            return $path;
        }
        $parts = explode('/',$path);
        $alias = array_shift($parts);
        $peanutPath = TConfiguration::getValue('peanutRootPath','peanut','');
        switch($alias) {
            case '@app'  : $alias = TConfiguration::getValue('mvvmPath','peanut',''); break;
            case '@core' : $alias = $peanutPath.'/core'; break;
            case '@pkg'  : $alias = $peanutPath.'/packages'; break;
        }
        if (empty($parts)) {
            return $alias;
        }
        $path = $alias.'/'.join('/',$parts);
        return $path;
    }

    public static function getViewModelSettings($pathAlias)
    {
        if (!isset(self::$vmSettings)) {
            $path = TPath::getConfigPath();
            $packagePath = TConfiguration::getValue('peanutRootPath', 'peanut') . '/packages';
            self::$vmSettings = parse_ini_file($path . 'viewmodels.ini', true);
            $packages = self::getPackageList();
            if (!empty($packages)) {
                $fileRoot = TPath::getFileRoot();
                $packagePath = $fileRoot . $packagePath;

                foreach ($packages as $package) {
                    $pkgini = @parse_ini_file($packagePath . "/$package/config/viewmodels.ini", true);
                    if (!empty($pkgini)) {
                        $keys = array_keys($pkgini);
                        foreach ($keys as $key) {
                            $pkgini[$key]['package'] = $package;
                        }
                        self::$vmSettings = array_merge(self::$vmSettings, $pkgini);
                    }
                }
            }
        }

        $key = strtolower($pathAlias);
        if (array_key_exists($key, self::$vmSettings)) {
            $item = self::$vmSettings[$key];
            $vmName = empty($item['vm']) ? array_pop(explode('/', $pathAlias)) : $item['vm'];

            $view = empty($item['view']) ? $vmName . '.html' : $item['view'];
            if (empty($item['package'])) {
                $root = TConfiguration::getValue('mvvmPath', 'peanut', 'applition/mvvm');
            }
            else {
                $root =  TConfiguration::getValue('peanutRootPath','peanut','pnut')."/packages/".$item['package'];
                $vmName = "@pkg/" . $item['package']."/$vmName";
            }

            $result = new TViewModelInfo();
            $result->pathAlias = $pathAlias;
            $result->vmName = $vmName;
            if ($view == 'content') {
                $result->view = $view;
            } else {
                $location = empty($item['location']) ? '': '/'.$item['location'];
                $result->view = $root."$location/view/$view";
            }
            self::$info = $result;
            return $result;
        }
        return false;
    }

    public static function getViewModelInfo()
    {
        return isset(self::$info) ? self::$info : false;
    }

    public static function RenderMessageElements() {
        if (!empty(self::$info)) {
            print "\n<div id='service-messages-container'><service-messages></service-messages></div>\n";
        }
    }

    public static function RenderStartScript()
    {
        if (!empty(self::$info)) {
            print self::GetStartScript();
        }

    }
    public static function GetStartScript()
    {
        if (empty(self::$info)) {
            return '';
        }
        $vmName = self::$info->vmName;
        // print "\n<!-- start script for '$vmName' goes here -->\n";

        return
            "\n<script>\n" .
            "   Peanut.PeanutLoader.startApplication('$vmName'); \n"
            . "</script>\n";

    }

    /**
     * See if this request is related to a ViewModel.
     * Check self::$info which is set by Initialize()
     *
     * @return bool
     */
    public static function hasVm()
    {
        return !empty(self::$info);
    }


}