<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 1/17/2019
 * Time: 5:46 PM
 */

namespace Tops\sys;


class TAddUserAccountResponse
{
    public function __construct($user=null)
    {
        $this->user = $user;
    }

    public $user = null;
    public $errorCode = false;
    public $invalidRoles = [];
    public $invalidProperties = [];
}