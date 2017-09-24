<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 9/14/2017
 * Time: 4:54 PM
 */

namespace Tops\sys;

/**
 * Class NullPermissionsManager
 * @package Tops\sys
 *
 * Use if no permission manager available.  No permissions are defined.  Exceptions raised for any managment functions.
 */

class NullPermissionsManager implements IPermissionsManager
{

    /**
     * @param string $roleName
     * @return bool
     */
    public function addRole($roleName, $roleDescription = null)
    {
        throw new \Exception('Role management not supported.');
    }

    /**
     * @param string $roleName
     * @return bool
     */
    public function removeRole($roleName)
    {
        throw new \Exception('Role management not supported.');
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
        throw new \Exception('Role management not supported.');
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
        throw new \Exception('Role management not supported.');
    }

    /**
     * @param string $roleName
     * @param string $permissionName
     * @return bool
     */
    public function revokePermission($roleName, $permissionName)
    {
        throw new \Exception('Role management not supported.');
    }
}