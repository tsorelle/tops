<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 10/12/2017
 * Time: 8:15 AM
 */

namespace Tops\sys;

abstract class TPermissionsManager
{
    const roleKeyFormat = TStrings::dashedFormat;
    const roleNameFormat = TStrings::wordCapsFormat;
    const roleDescriptionFormat = TStrings::wordCapsFormat;
    const permisssionNameFormat = TStrings::dashedFormat;
    const permissionDescriptionFormat = TStrings::initialCapFormat;

    const adminRole = 'administrator';
    const authenticatedRole = 'authenticated';
    const guestRole = 'guest';
    const permissionsClassKey = 'tops.permissions';
    const appAdminRoleName = 'Peanut Administrator';
    const appAdminPermissionName = 'Administer peanut features';
    const mailAdminRoleName = 'Mail Administrator';
    const mailAdminPermissionName = 'Administer mailboxes';
    const directoryAdminRoleName = 'Directory Administrator';
    const directoryAdminPermissionName = 'Administer directory';
    const viewDirectoryPermissionName = 'View directory';
    const updateDirectoryPermissionName = 'Update directory';
    /**
     * @var TPermissionsManager
     */
    private static $permissionManger;

    /**
     * @return TPermissionsManager
     */
    public static  function getPermissionManager()
    {
        if (!isset(self::$permissionManger)) {
            if (TObjectContainer::HasDefinition(TPermissionsManager::permissionsClassKey)) {
                self::$permissionManger = TObjectContainer::Get(TPermissionsManager::permissionsClassKey);
            }
            else {
                self::$permissionManger = new NullPermissionsManager();
            }
        }
        return self::$permissionManger;
    }

    private $virtualRoles;
    protected function getVirtualRoles() {
        if (!isset($this->virtualRoles)) {
            $this->virtualRoles = [];
            $this->virtualRoles[self::authenticatedRole] =
                $this->createRoleObject(
                    TPermissionsManager::authenticatedRole,
                    'Authenticated user',
                    'Current logged in user'
                );

            $this->virtualRoles[self::authenticatedRole] =
                $this->createRoleObject(
                    TPermissionsManager::authenticatedRole,
                    'Guest',
                    'Anonymous user'
                );
        }
        return $this->virtualRoles;
    }

    protected function getVirtualRoleAuthenticated() {
        $this->getVirtualRoles();
        return $this->virtualRoles[self::authenticatedRole];
    }

    protected function getVirtualRoleGuest() {
        $this->getVirtualRoles();
        return $this->virtualRoles[self::guestRole];
    }

    protected function createRoleObject($key,$name=null,$description=null)
    {
        if ($name === null) {
            $name = $key;
        }
        if ($description == null) {
            $description = $key;
        }
        $role = new \stdClass();
        $role ->Key = TStrings::ConvertNameFormat($key,$this->getRoleKeyFormat());
        $role ->Name = TStrings::ConvertNameFormat($name,$this->getRoleNameFormat());
        $role ->Description = TStrings::ConvertNameFormat($description,$this->getRoleDescriptionFormat());
        return $role;
    }


    public function getRoleNameFormat() {
        return self::roleNameFormat;
    }

    public function getRoleKeyFormat() {
        return self::roleKeyFormat;
    }

    public function getRoleDescriptionFormat() {
        return self::roleDescriptionFormat;
    }

    /*
     * Used to match format returnd by user roles routines. This may vary for cms
     */
    public function getRoleIdFormat() {
        return $this->getRoleNameFormat();
    }

    /**
     * @param string $roleName
     * @return bool
     */
    public abstract function addRole($roleName, $roleDescription = null);

    /**
     * @param string $roleName
     * @return bool
     */
    public abstract function removeRole($roleName);

    /**
     * @return [];
     *
     * return array of stdClass
     *  interface ILookupItem {
     *     Key: any;
     *     Text: string;
     *     Description: string;
     *   }
     */
    public abstract function getRoles();

    /**
     * @return TPermission[]
     */
    public abstract function getPermissions();

    public abstract function addPermission($name, $description);

    public abstract function removePermission($name);

    /**
     * @return TPermission
     */
    public abstract function getPermission($permissionName);

    /**
     * @param string $roleName
     * @param string $permissionName
     * @return bool
     */
    public abstract function assignPermission($roleName, $permissionName);

    /**
     * @param string $roleName
     * @param string $permissionName
     * @return bool
     */
    public abstract function revokePermission($roleName, $permissionName);

    public function getAuthenticatedRole() {
        return self::authenticatedRole;
    }

    public function getAdminRole() {
        return self::adminRole;
    }

    public function getGuestRole() {
        return self::guestRole;
    }

    public function formatRoleName($roleName) {
        if ($roleName == self::authenticatedRole) {
            $roleName = $this->getAuthenticatedRole();
        }
        else if ($roleName == self::guestRole) {
            $roleName = $this->getGuestRole();
        }
        return TStrings::ConvertNameFormat($roleName,$this->getRoleIdFormat());
    }


}