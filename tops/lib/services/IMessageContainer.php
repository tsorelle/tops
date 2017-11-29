<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 7/7/2015
 * Time: 6:37 AM
 */

namespace Tops\services;


interface IMessageContainer {
    public function AddMessage($messageType,$text,$translated=false);
    public function AddInfoMessage($text,$translated=false);
    public function AddWarningMessage($text,$translated=false);
    public function AddErrorMessage($text,$translated=false);
    public function GetResult();
}