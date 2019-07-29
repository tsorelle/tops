<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 7/29/2019
 * Time: 8:43 AM
 */

namespace TwoQuakers\testing;


use Tops\sys\IUserAccountManager;
use Tops\sys\TAddUserAccountResponse;

class TestUserAccountManager implements IUserAccountManager
{

    /**
     * @param $username
     * @return number | null
     */
    public function getCmsUserId($username)
    {
        // TODO: Implement getCmsUserId() method.
        return 1;
    }

    /**
     * @param $email
     * @return number | null
     */
    public function getCmsUserIdByEmail($email)
    {
        // TODO: Implement getCmsUserIdByEmail() method.
        return 1;
    }

    /**
     * @return TAddUserAccountResponse
     */
    public function addAccount($username, $password, $email = null, $roles = [], $profile = [])
    {
        $response = new TAddUserAccountResponse();
        $response->userId = 1;
        return $response;
    }

    public function getPasswordResetUrl()
    {
        // TODO: Implement getPasswordResetUrl() method.
        return null;
    }

    public function getLoginUrl()
    {
        // TODO: Implement getLoginUrl() method.
        return null;
    }
}