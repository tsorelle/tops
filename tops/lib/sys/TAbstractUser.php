<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 2/10/2015
 * Time: 8:22 AM
 */

namespace Tops\sys;


abstract class TAbstractUser implements IUser
{
    protected $id = 0;
    protected $userName;
    protected $isCurrentUser;

    /**
     * @var array
     */
    protected   $profile = null;

    protected abstract function test();

    /**
     * @param $id
     * @return mixed
     */
    public abstract function loadById($id);

    /**
     * @param $email
     * @return mixed
     */
    public abstract function loadByEmail($email);

    /**
     * @param $userName
     * @return mixed
     */
    public abstract function loadByUserName($userName);

    /**
     * @return mixed
     */
    public abstract function loadCurrentUser();

    /**
     * @return bool
     */
    public abstract function isAdmin();

    /**
     * @return string[]
     */
    public abstract function getRoles();

    /**
     * @param $roleName
     * @return bool
     */
    public abstract function isMemberOf($roleName);

    /**
     * @param string $value
     * @return bool
     */
    public function isAuthorized($value = '') {
        if ($this->isAdmin()) {
            return true;
        }
        if ($value == 'authenticated' && $this->isAuthenticated()) {
            return true;
        }

        return TUser::getPermissionManager()->verifyPermission($value);
    }

    /**
     * @return bool
     */
    public abstract function isAuthenticated();

    protected abstract function loadProfile();

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }  //  getId

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->getProfileValue(TUser::profileKeyFirstName);
    }  //  getFirstName

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->getProfileValue(TUser::profileKeyLastName);

    }  //  getLastName

    /**
     * @return string
     */
    public function getUserName()
    {
        if (isset($this->userName)) {
            return $this->userName;
        }
        return TUser::DefaultUserName;
    }  //  getUserName

    /**
     * @param bool $defaultToUsername
     * @return string
     */
    public function getFullName($defaultToUsername = true)
    {
        if (!isset($this->userName)) {
            return '';
        }
        if ($this->userName == 'admin') {
            return "The administrator";
        }
        $name = $this->getProfileValue(TUser::profileKeyFullName);
        if (empty($name)) {
            if ($defaultToUsername) {
                return $this->userName;
            }
            return '';
        }
        return $name;
    }  //  getfullName

    /**
     * @param bool $defaultToUsername
     * @return string
     */
    public function getUserShortName($defaultToUsername = true)
    {
        $name = $this->getProfileValue(TUser::profileKeyShortName);
        if (empty($name)) {
            return $this->getFullName($defaultToUsername);
        }
        return $name;

    }  //  getfullName


    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->getProfileValue(TUser::profileKeyEmail);
    }  //  getEmail

    public function isCurrent()
    {
        return  !empty($this->isCurrentUser);
    }

    protected function setCurrent()
    {
        $this->isCurrentUser = true;
    }

    public function getProfileValue($key) {
        if ($this->isAuthenticated()) {
            if (!isset($this->profile)) {
                $this->loadProfile();
            }

            if (array_key_exists($key, $this->profile)) {
                return $this->profile[$key];
            }
            return false;
        }
        return '';
    }

    public function setProfileValue($key,$value) {
        if (!isset($this->profile)) {
            $this->loadProfile();
        }
        $isUpdate = array_key_exists($key,$this->profile) ;
        $this->profile[$key] = $value;
        if ($isUpdate) {
            $this->updateProfile($key);
        }
    }

    public function updateProfile($key=null) {
        // override in sub-class as needed
    }

    public function getContentTypes() {
        // overide in subclass as needed
        return array();
    }

    public function getUserPicture($size=0, array $classes = [], array $attributes = []) {
        return ''; // override in subclasses as deisire.
    }


}