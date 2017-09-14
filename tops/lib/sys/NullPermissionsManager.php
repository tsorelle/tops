<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 9/14/2017
 * Time: 4:54 PM
 */

namespace Tops\sys;

// Use for testing

class NullPermissionsManager implements IPermissionsManager
{

    /**
     * @param string $roleName
     * @return bool
     */
    public function addRole($roleName, $roleDescription = null)
    {
        return true;
    }

    /**
     * @param string $roleName
     * @return bool
     */
    public function removeRole($roleName)
    {
        return true;
    }

    /**
     * @return string[]
     */
    public function getRoles()
    {
        return array();
    }

    /**
     * @return TPermission[]
     */
    public function getPermissions()
    {
        return array();
    }

    public function addPermission($name, $description)
    {
        return true;
    }

    public function getPermission($permissionName)
    {
        return false;
    }

    /**
     * @param string $roleName
     * @param string $permissionName
     * @return bool
     */
    public function assignPermission($roleName, $permissionName)
    {
        return true;
    }

    /**
     * @param string $roleName
     * @param string $permissionName
     * @return bool
     */
    public function revokePermission($roleName, $permissionName)
    {
        return true;
    }
}