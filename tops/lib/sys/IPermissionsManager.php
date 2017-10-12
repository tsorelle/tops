<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 9/10/2017
 * Time: 6:12 AM
 */

namespace Tops\sys;

/**
 * Interface IPermissionsManager
 * @package Tops\sys
 * @deprecated use TPermissionsManager
 */
interface IPermissionsManager
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
     * @return [];
     *
     * return array of stdClass
     *  interface ILookupItem {
     *     Key: any;
     *     Text: string;
     *     Description: string;
     *   }
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