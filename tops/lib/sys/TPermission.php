<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 9/11/2017
 * Time: 4:33 AM
 */

namespace Tops\sys;


class TPermission
{
    public $id;
    public $permissionName;
    public $description;
    public $active = 1;
    private $roles = array();

    public function getId() { return $this->id;}
    public function getPermissionName() { return $this->permissionName;}
    public function getDescription() { return $this->description;}
    public function getRoles() { return $this->roles;}

    public function check($roleName) {
        return in_array($roleName,$this->roles);
    }
    public function setId($value) {$this->id = $value;}
    public function setPermissionName($value) {$this->permissionName = $value;}
    public function setDescription($value) {$this->description = $value;}
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