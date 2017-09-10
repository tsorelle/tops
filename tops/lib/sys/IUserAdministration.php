<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 9/10/2017
 * Time: 6:12 AM
 */

namespace Tops\sys;


interface IUserAdministration
{
    /**
     * @param $roleName
     * @param string[] $permissions
     * @return bool
     */
    public function addRole($roleName,array $permissions = array());

    /**
     * @return string[]
     */
    public function getRoles();

    /*
    public function getPermissions();
    public function assignPermission($roleName,$permission);
    public function addPermission($permission);
    public function assingUserToRole($user, $role);
    */

}