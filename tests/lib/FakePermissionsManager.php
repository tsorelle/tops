<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 9/11/2017
 * Time: 4:59 AM
 */

namespace TwoQuakers\testing;


use Tops\sys\IPermissionsManager;
use Tops\sys\TPermission;

class FakePermissionsManager implements IPermissionsManager
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
    public function addRole($roleName)
    {
        $exists = isset($this->roles[$roleName]);
        if (!$exists) {
            $this->roles[] = $roleName;
        }
        return (!$exists);
    }

    /**
     * @return string[]
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @return TPermission[]
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    public function getPermission($permissionName)
    {
        if (!array_key_exists($permissionName,$this->permissions)) {
            return false;
        }
        return $this->permissions[$permissionName];
    }

    /**
     * @param string $roleName
     * @param string $permissionName
     * @return bool
     */
    public function assignPermission($roleName, $permissionName)
    {
        $result = false;
        $permission = $this->getPermission($permissionName);
        if ($permission !== false) {
            $permission->addRole($roleName);
            $this->permissions[$permissionName] = $permission;
        }
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
     * @param string $permissionName
     * @return bool
     */
    public function revokePermission($roleName, $permissionName)
    {
        // TODO: Implement revokePermission() method.
    }
}