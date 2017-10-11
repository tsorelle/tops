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

        // override to implement cms specific routines
        return false;
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

    protected function getDefaultUserName() {
        if (!$this->isAuthenticated()) {
            return TUser::anonymousDisplayName;
        }
        if ($this->userName == 'admin') {
            return "The administrator";
        }
        return false;
    }

    /**
     * @param bool $defaultToUsername
     * @return string
     */
    public function getFullName($defaultToUsername = true)
    {
        $name = $this->getDefaultUserName();
        if (empty($name)) {
            $name = $this->getProfileValue(TUser::profileKeyFullName);
            if (empty($name)) {
                $name = $this->getProfileValue(TUser::profileKeyDisplayName);
                if (empty($name)) {
                    $name = $this->getProfileValue(TUser::profileKeyShortName);
                    if (empty($name)) {
                        $name = $defaultToUsername ? $this->getUserName() : '';
                    }
                }
            }
        }
        return $name;
    }  //  getfullName

    /**
     * @param bool $defaultToUsername
     * @return string
     */
    public function getShortName($defaultToUsername = true)
    {
        $name = $this->getDefaultUserName();
        if (empty($name)) {
            $name = $this->getProfileValue(TUser::profileKeyShortName);
            if (empty($name)) {
                $name = $this->getProfileValue(TUser::profileKeyDisplayName);
                if (empty($name)) {
                    $name = $this->getProfileValue(TUser::profileKeyFullName);
                    if (empty($name)) {
                        $name = $defaultToUsername ? $this->getUserName() : '';
                    }
                }
            }
        }
        return $name;
    }  //  getShortName

    public function getDisplayName($defaultToUsername = true)
    {
        $name = $this->getDefaultUserName();
        if (empty($name)) {
            $name = $this->getProfileValue(TUser::profileKeyDisplayName);
            if (empty($name)) {
                $name = $this->getProfileValue(TUser::profileKeyFullName);
                if (empty($name)) {
                    $name = $this->getProfileValue(TUser::profileKeyShortName);
                    if (empty($name)) {
                        $name = $defaultToUsername ? $this->getUserName() : '';
                    }
                }
            }
        }
        return $name;
    }

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
        $key = $this->formatProfileKey($key);
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
        $key = $this->formatProfileKey($value);
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

    public function getUserPicture($size=0, array $classes = [], array $attributes = []) {
        return ''; // override in subclasses as deisired.
    }

    protected function formatProfileKey($key) {
        return TStrings::convertNameFormat($key,TStrings::dashedFormat);
    }

}