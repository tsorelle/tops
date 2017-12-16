<?php
/**
 * Created by PhpStorm.
 * User: terry
 * Date: 5/14/2017'
 * Time: 6:01 AM
 */
$projectFileRoot =   str_replace('\\','/', realpath(__DIR__.'/..')).'/';
global $_SESSION;
if (!isset($_SESSION)) {
    $_SESSION = [];
}
\Tops\sys\TPath::Initialize($projectFileRoot,'tests/config');

