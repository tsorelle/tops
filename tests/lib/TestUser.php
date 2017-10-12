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
use Tops\sys\TPath;

class TestUser extends TAbstractUser
{
    private static $permissionsConfig;
    private static $rolesConfig;

    public function __construct()
    {
        if (isset(self::$rolesConfig)) {
            return;
        }
        self::$rolesConfig = array();
        self::$permissionsConfig = array();
        $path = TPath::getConfigPath() . 'users.ini';
        $ini = parse_ini_file($path, true);
        if (!empty($ini)) {
            if (!empty($ini['user']['roles'])) {
                self::$rolesConfig = explode(',',$ini['user']['roles']);
            }
            if (!empty($ini['permissions'])) {
                self::$permissionsConfig = $ini['permissions'];
            }
        }
    }

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
        if (parent::isAuthorized($value)) {
            return true;
        }

        if (empty(self::$permissionsConfig[$value]) ) {
            return false;
        }

        $roles = self::$permissionsConfig[$value];

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
        return self::$roles;
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