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
$_SESSION['tops-session-cache'] = [];
include  __DIR__.'\..\vendor\autoload.php';
\Tops\sys\TPath::Initialize($projectFileRoot,'tests/config');

