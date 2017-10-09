<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 9/11/2017
 * Time: 4:33 AM
 */

namespace Tops\sys;


class TPermission extends TBasicPermission
{
    private $roles = array();

    /**
     * @return string[]
     */
    public function getRoles() { return $this->roles;}

    /**
     * @param $roleName
     * @return bool
     */
    public function check($roleName) {
        $roleName = TStrings::convertNameFormat($roleName,IPermissionsManager::roleNameFormat);
        return in_array($roleName,$this->roles);
    }
    public function addRole($roleName) {
        $roleName = TStrings::convertNameFormat($roleName,IPermissionsManager::roleNameFormat);
        if (!in_array($roleName,$this->roles)) {
            $this->roles[] = $roleName;
        }
    }

    public function removeRole($roleName)
    {
        $this->roles = array_diff($this->roles, [$roleName]);
    }

    public static function Create($name,$description) {
        $result = new TPermission();
        $result->permissionName = $name;
        $result->description = $description;
        return $result;
    }

}