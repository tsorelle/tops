<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 4/24/2015
 * Time: 4:15 AM
 */

namespace Tops\sys;



use Tops\services\IMessageContainer;
use Tops\sys\IUser;

interface IUserFactory {
    /**
     * @return IUser
     */
    public function createUser();

    /**
     * @return IUser
     */
    public function addAccount(IMessageContainer $client, $username,$password,$email=null,$roles=[],$profile=[]);

}