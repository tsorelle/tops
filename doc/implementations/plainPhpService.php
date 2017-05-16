<?php
/**
 * Created by PhpStorm.
 * User: terry
 * Date: 5/16/2017
 * Time: 8:27 AM
 */
// Assumes script at document root. And using Composer autoloading
include __DIR__."/vendor/autoload.php";
\Tops\services\ServiceFactory::Execute();