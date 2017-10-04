<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 2/10/2015
 * Time: 8:22 AM
 */

namespace Tops\sys;


use Tops\cache\ITopsCache;
use Tops\cache\TSessionCache;
use Tops\sys\TConfiguration;

abstract class TAbstractUser implements IUser
{
    protected $id = 0;
    protected $userName;
    protected $isCurrentUser;

    /**
     * @var array
     */
    protected   $profile = null;

    private function getCachedProfile() {
        $cache = TUser::getProfileCache();
        $result = $cache->Get('users.'.$this->userName);
        return $result;
    }

    private function cacheProfile() {
        $cache = TUser::getProfileCache();
        $result = $cache->Set('users.'.$this->userName,$this->profile,20);
        return $result;
    }


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

    protected function loadProfileValues()
    {
        $this->profile = $this->getCachedProfile();
        if ($this->profile === null) {
            $this->profile = array();
            $this->loadProfile();
            $this->cacheProfile();
        }
    }

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
            $name = $this->$this->getProfileValue(TUser::profileKeyShortName);
            if (empty($name) && $defaultToUsername) {
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
            $name = $this->getProfileValue(TUser::profileKeyFullName);
            if (empty($name)) {
                return $this->getUserName();
            }
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
                $this->loadProfileValues();
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
            $this->loadProfileValues();
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
        return ''; // override in subclasses as deisired.
    }

    protected function getProfileFieldKey($key) {
        $default = str_replace('-','_',$key);
        return TConfiguration::getValue($key,'user-attributes',$default);
    }
}