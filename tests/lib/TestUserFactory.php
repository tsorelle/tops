<?php
/**
 * Created by PhpStorm.
 * User: terry
 * Date: 5/15/2017
 * Time: 10:46 AM
 */

namespace TwoQuakers\testing;


use Tops\services\IMessageContainer;
use Tops\sys\IUser;

class TestUserFactory implements \Tops\sys\IUserFactory
{

    /**
     * @return IUser
     */
    public function createUser()
    {
        return new TestUser();
    }

    /**
     * @return IUser
     */
    public function addAccount(IMessageContainer $client, $username, $password,  $email=null, $roles=[],  $profile = [])
    {
        return $this->createUser();
    }
}