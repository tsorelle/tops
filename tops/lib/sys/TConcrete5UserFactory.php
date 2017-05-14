<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 3/30/2017
 * Time: 7:15 AM
 */

namespace Tops\sys;


use Tops\sys\TConcrete5User;

class TConcrete5UserFactory implements IUserFactory
{

    /**
     * @return IUser
     */
    public function createUser()
    {
        return new TConcrete5User();
    }
}