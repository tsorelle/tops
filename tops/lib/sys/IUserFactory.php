<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 4/24/2015
 * Time: 4:15 AM
 */

namespace Tops\sys;


interface IUserFactory {
    const duplicateUsernameError = 'account-error-duplicate-name';
    const duplicateEmailError = 'account-error-duplicate-email';
    const addAccountError = 'account-error-add-failed';
    const addAccountParameterError = 'account-error-bad-args';

    /**
     * @return IUser
     */
    public function createUser();

    /**
     * @return TAddUserAccountResponse
     */
    public function addAccount($username,$password,$email=null,$roles=[],$profile=[]);

}