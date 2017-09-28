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
        return in_array($roleName,$this->roles);
    }
    public function addRole($value) {
        if (!in_array($value,$this->roles)) {
            $this->roles[] = $value;
        }
    }
    public function setRoles($value) {$this->roles[] = $value;}

    public static function Create($name,$description) {
        $result = new TPermission();
        $result->permissionName = $name;
        $result->description = $description;
        return $result;
    }

}