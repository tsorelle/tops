<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 9/10/2017
 * Time: 6:12 AM
 */

namespace Tops\sys;


interface IPermissionsManager
{
    /**
     * @param string $roleName
     * @return bool
     */
    public function addRole($roleName,$roleDescription=null);

    /**
     * @param string $roleName
     * @return bool
     */
    public function removeRole($roleName);

    /**
     * @return string[]
     */
    public function getRoles();

    /**
     * @return TPermission[]
     */
    public function getPermissions();

    public function addPermission($name, $description);

    public function removePermission($name);

    /**
     * @return TPermission
     */
    public function getPermission($permissionName);

    /**
     * @param string $roleName
     * @param string $permissionName
     * @return bool
     */
    public function assignPermission($roleName, $permissionName);

    /**
     * @param string $roleName
     * @param string $permissionName
     * @return bool
     */
    public function revokePermission($roleName, $permissionName);

    // public function verifyPermission($permissionName);

}