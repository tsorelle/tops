<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 9/8/2017
 * Time: 4:15 PM
 */

namespace TwoQuakers\testing;


use Tops\mail\IMailbox;
use Tops\mail\IMailboxManager;
use Tops\mail\TMailbox;

class FakeMailboxManager implements IMailboxManager
{

    protected $boxes = array();

    /**
     *
     * @param $id
     * @return IMailbox
     */
    public function find($id)
    {

        // TODO: Implement find() method.
        return null;
    }

    /**
     * @param $id
     */
    public function drop($id)
    {
        // TODO: Implement drop() method.
    }

    /**
     * @param $mailboxCode
     * @return bool|TMailbox
     */
    public function findByCode($mailboxCode)
    {
        if (!array_key_exists($mailboxCode,$this->boxes)) {
            return false;
        }
        return $this->boxes[$mailboxCode];
    }

    /**
     * @param null $filter
     * @return IMailbox[]
     */
    public function getMailboxes($showAll=false)
    {
        // TODO: Implement getMailboxes() method.
    }

    /**
     * @param $code
     * @param $name
     * @param $address
     * @param $description
     * @return IMailbox
     */
    public function addMailbox($code, $name, $address, $description)
    {
        $box = new TMailbox();
        $box->setMailboxId(sizeof($this->boxes) + 1);
        $box->setMailboxCode($code);
        $box->setName($name);
        $box->setEmail($address);
        $box->setDescription($description);
        $this->boxes[$code] = $box;
    }

    /**
     * @param IMailbox $mailbox
     * @return bool
     */
    public function updateMailbox(IMailbox $mailbox)
    {
        // TODO: Implement updateMailbox() method.
    }

    /**
     * @param $code
     * @param $name
     * @param $address
     * @param $description
     * @return IMailbox
     */
    public function createMailbox($code, $name, $address, $description)
    {
        // implement later
        return null;
    }

    public function saveChanges()
    {
    }

    /**
     * @param $mailboxCode string
     */
    public function remove($mailboxCode)
    {
    }

    /**
     * @param $mailboxCode string
     */
    public function restore($mailboxCode)
    {
    }
}