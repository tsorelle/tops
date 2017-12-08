<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 1/22/2015
 * Time: 2:34 PM
 */

namespace Tops\mail;


/**
 * Interface IMailboxManager
 * @package Tops\sys
 */
interface IMailboxManager {
    // system mailbox names
    const AdminBox = 'admin';
    const BounceBox = 'bounce';
    const VendorBox = 'two-quakers-support';
    const ContactBox = 'contact-form';

    /**
     *
     * @param $id
     * @return IMailbox
     */
    public function find($id);

    /**
     * @param $id
     */
    public function drop($id);

    /**
     * @param $id
     */
    public function remove($mailboxCode);

    /**
     * @param $mailboxCode string
     */
    public function restore($mailboxCode);


    /**
     * @param $mailboxCode string
     * @return bool|IMailbox
     */
    public function findByCode($mailboxCode);

    /**
     * @param null $filter
     * @return \stdClass[]
     */
    public function getMailboxes($showAll=false);

    /**
     * @param $code
     * @param $name
     * @param $address
     * @param $description
     * @return IMailbox
     */
    public function addMailbox($code,$name,$address,$description);

    /**
     * @param IMailbox $mailbox
     * @return bool
     */
    public function updateMailbox(IMailbox $mailbox);

    /**
     * @param $code
     * @param $name
     * @param $address
     * @param $description
     * @return bool|IMailbox
     */
    public function createMailbox($code, $name, $address, $description);

    public function saveChanges();

}