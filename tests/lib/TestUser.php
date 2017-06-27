<?php
/**
 * Created by PhpStorm.
 * User: terry
 * Date: 5/15/2017
 * Time: 10:40 AM
 */

namespace TwoQuakers\testing;


use PHPUnit\Runner\Exception;
use Tops\sys\TAbstractUser;
use Tops\sys\TConfiguration;

class TestUser extends TAbstractUser
    // implements \Tops\sys\IUser
{

    /**
     * @param $id
     * @return mixed
     */
    public function loadById($id)
    {
        $this->loadByUserName($id == 1 ? 'admin' : 'tester');
    }

    /**
     * @param $userName
     * @return mixed
     */
    public function loadByUserName($userName)
    {
        $this->userName = $userName;
        if ($userName == 'admin') {
            $this->id = 1;
            $this->roles = array('admin');
        }
        else {
            $this->id = 2;
            $this->roles = array('finance');
        }
    }

    /**
     * @return mixed
     */
    public function loadCurrentUser()
    {
        $this->loadByUserName('tester');
    }

    /**
     * @param $roleName
     * @return bool
     */
    public function isMemberOf($roleName)
    {
        return (
            $this->isAdmin() || in_array($roleName,$this->roles)
        );
    }

    /**
     * @return bool
     */
    public function isAuthenticated()
    {
        return true;
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isAuthorized($value = '')
    {
        if ($this->isAdmin()) {
            return true;
        }
        $roles = TConfiguration::getValue($value,'permissions');
        if (empty($roles)) {
            return false;
        }
        if ($roles == 'authenticated') {
            return $this->isAuthenticated();
        }
        $roles = explode(',',$roles);
        foreach ($roles as $value) {
            if (in_array($value,$this->roles)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return in_array('admin',$this->roles);
    }

    public function getProfileValue($key)
    {
        switch ($key) {
            case 'firstName' : return 'Tommy';
            case 'lastName' : return 'Tester';
            case  'fullName' : return 'Tommy the Tester';
            case 'shortName' : return 'Tom';
            case 'email' : return 'tommy@testing.com';
        }
        return '';
    }


    protected function test()
    {
        return 'TestUser';
    }

    /**
     * @return string[]
     */
    public function getRoles()
    {
        // TODO: Implement getRoles() method.
    }

    protected function loadProfile()
    {
        $this->profile = array(
            'firstName' => 'Tommy',
            'lastName' => 'Tester',
            'fullName' => 'Tommy the Tester',
            'shortName' => 'Tom',
            'email' => 'tommy@testing.com'
        );
    }

    /**
     * @param $email
     * @return mixed
     */
    public function loadByEmail($email)
    {
        throw new Exception("Unsupported method 'loadByEmail");
    }
}