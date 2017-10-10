<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 2/11/2015
 * Time: 7:01 AM
 */

namespace Tops\sys;


use Tops\cache\ITopsCache;
use Tops\cache\TSessionCache;

class TUser {

    const AdminRole = 'administrator';
    const DefaultUserName = 'guest';
    const anonymousDisplayName = 'Guest';
    const AuthenticatedRole = 'authenticated';
    const PermissionsClassKey = 'tops.permissions';
    const UserFactoryClassKey = 'tops.userfactory';
    const profileKeyFullName  ='full-name';
    const profileKeyShortName ='short-name';
    const profileKeyDisplayName ='display-name';
    const profileKeyEmail     ='email';
    const appAdminRoleName = 'Peanut Administrator';
    const appAdminPermissionName = 'Can administer peanut features';
    const mailAdminRoleName = 'Mail Administrator';
    const mailAdminPermissionName = 'Can administer mailboxes';
    const directoryAdminRoleName = 'Directory Administrator';
    const directoryAdminPermissionName = 'Can administer directory';
    const viewDirectoryPermissionName = 'Can view directory';
    const updateDirectoryPermissionName = 'Can update directory';

    /**
     * @var IUser
     */
    private static $currentUser;

    /**
     * @return IUser
     */
    public static function getCurrent() {
        if (!isset(self::$currentUser)) {
            self::$currentUser = self::Create();
            self::$currentUser->loadCurrentUser();
        }
        return self::$currentUser;
    }

    /**T
     * @param IUser $user
     */
    public static function setCurrentUser(IUser $user) {
        self::$currentUser = $user;
    }

    public static function setCurrent($userName)
    {
        if (!(isset(self::$currentUser) && self::$currentUser->getUserName() == $userName)) {
            self::$currentUser = self::Create();
            self::$currentUser->loadByUserName($userName);
        }
        return self::$currentUser;
    }

    public static function getByUserName($userName) {

        $result = self::Create();
        $result->loadByUserName($userName);
        return $result;
    }

    public static function getById($uid) {
        $result = self::Create();
        $result->loadById($uid);
        return $result;
    }

    public static function getByEmail($email) {
        $result = self::Create();
        $result->loadByEmail($email);
        return $result;
    }

    private static $userFactory;
    public static function setUserFactory(IUserFactory $factory)
    {
        // set factory for testing routines
        self::$userFactory = $factory;
    }

    /**
     * @return IUserFactory
     */
    private static function getUserFactory() {
        if (!isset(self::$userFactory)) {
            if (TObjectContainer::HasDefinition('tops.userfactory')) {
                self::$userFactory = TObjectContainer::Get('tops.userfactory');
            }
            else {
                self::$userFactory = new TNullUserFactory();
            }
        }
        return self::$userFactory;
    }

    /**
     * @return IUser
     */
    public static function Create() {

        return self::getUserFactory()->createUser();

    }

    /**
     * @var IPermissionsManager
     */
    private static $permissionManger;
    public static  function getPermissionManager()
    {
        if (!isset(self::$permissionManger)) {
            if (TObjectContainer::HasDefinition(self::PermissionsClassKey)) {
                self::$permissionManger = TObjectContainer::Get(self::PermissionsClassKey);
            }
            else {
                self::$permissionManger = new NullPermissionsManager();
            }
        }
        return self::$permissionManger;
    }

    /**
     * @var ITopsCache
     */
    private static $profileCache;

    public static function clearProfiles() {
        self::getProfileCache()->Flush('users');
    }

    /**
     * @return ITopsCache
     */
    public static function getProfileCache()
    {
        if (!isset(self::$profileCache)) {
            self::$profileCache = new TSessionCache();
        }
        return self::$profileCache;
    }

    /**
     * @param $key
     * @return string
     */
    public static function getProfileFieldKey($key,$format=TStrings::keyFormat) {
        $configKey = TStrings::convertNameFormat($key,TStrings::dashedFormat);
        $default = TStrings::convertNameFormat($key,$format);
        return TConfiguration::getValue($configKey,'user-attributes',$default);
    }

}