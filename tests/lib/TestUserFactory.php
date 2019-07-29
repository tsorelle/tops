<?php
/**
 * Created by PhpStorm.
 * User: terry
 * Date: 5/15/2017
 * Time: 10:46 AM
 */

namespace TwoQuakers\testing;


use Tops\sys\IUser;
use Tops\sys\IUserAccountManager;
use Tops\sys\TAddUserAccountResponse;

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
     * @return TAddUserAccountResponse
     */
    public function addAccount($username,$password,$email=null,$roles=[],$profile=[])
    {
        return  new TAddUserAccountResponse(
           // $this->createUser()
        );
    }

    // @var IUserAccountManager
    private static $accountManager;

    /**
     * @return IUserAccountManager
     */
    public function createAccountManager()
    {
        if (!isset(self::$accountManager)) {
            self::$accountManager = new TestUserAccountManager();
        }
        return self::$accountManager;
    }
}