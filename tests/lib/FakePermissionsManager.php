<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 9/11/2017
 * Time: 4:59 AM
 */

namespace TwoQuakers\testing;


use Tops\sys\TPermission;
use Tops\sys\TPermissionsManager;
use Tops\sys\TStrings;

class FakePermissionsManager extends TPermissionsManager
{

    private $roles = array('admin','member','reader','guest');
    /**
     * @var TPermission[];
     */
    private $permissions = array();

    /**
     * @param string $roleName
     * @return bool
     */
    public function addRole($roleName,$roleDescription='')
    {
        $exists = isset($this->roles[$roleName]);
        if (!$exists) {
            $this->roles[] = $roleName;
        }
        return (!$exists);
    }

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
    public function getRoles()
    {
        $result = [];
        foreach ($this->roles as $role) {
            $result[] = $this->createRoleObject($role);
        }
        return $result;
    }

    /**
     * @return TPermission[]
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    public function getPermission($permissionHandle)
    {
        if (!array_key_exists($permissionHandle,$this->permissions)) {
            return false;
        }
        return $this->permissions[$permissionHandle];
    }

    /**
     * @param string $roleName
     * @param string $permissionName
     * @return bool
     */
    public function assignPermission($roleHandle, $permissionHandle)
    {
        $permissionObject = $this->getPermission($permissionHandle);
        if ($permissionObject === false) {
            return false;
        }
        $permissionObject->addRole($roleHandle);
        $this->permissions[$permissionHandle] = $permissionObject;
        return true;
    }

    public function addPermission($name,$description='')
    {
        if (array_key_exists($name,$this->permissions)) {
            return false;
        }
        $permission = new TPermission();
        $permission->setPermissionName($name);
        $permission->setDescription($description);
        $this->permissions[$name] = $permission;
        return (true);
    }


    /**
     * @param string $roleName
     * @return bool
     */
    public function removeRole($roleName)
    {
        // TODO: Implement removeRole() method.
        return true;
    }

    public function removePermission($pemissionHandle)
    {
        // TODO: Implement removePermission() method.
    }

    /**
     * @param string $roleName
     * @param string $permissionName
     * @return bool
     */
    public function revokePermission($roleHandle, $permissionHandle)
    {
        // TODO: Implement revokePermission() method.
        return true;
    }
}