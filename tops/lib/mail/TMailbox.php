<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 1/22/2015
 * Time: 2:36 PM
 */

namespace Tops\mail;
use Tops\sys\TObjectContainer;


/**
 * Class TMailbox
 * @package Tops\sys
 */
class TMailbox implements IMailbox
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var string
     */
    public $mailboxcode;
    /**
     * @var string
     */
    public $displaytext;

    public $description;

    public $address;


    /**
     * @return int
     */
    public function getMailboxId()
    {
        return $this->id;
    }

    /**
     * @param int $mailBoxId
     */
    public function setMailboxId($mailBoxId)
    {
        $this->id = $mailBoxId;
    }

    /**
     * @return string
     */
    public function getMailboxCode()
    {
        return $this->mailboxcode;
    }

    /**
     * @param string $mailBoxCode
     */
    public function setMailboxCode($mailBoxCode)
    {
        $this->mailboxcode = $mailBoxCode;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->displaytext;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->displayText = $name;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->address;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->address = $email;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
    /**
     * @var string
     */

    /**
     * @param $code
     * @param $name
     * @param $address
     * @param $description
     * @return TMailbox
     */
    public static function Create ($code, $name, $address, $description,$id=0) {
        $result = new TMailbox();
        $result->setMailboxCode($code);
        $result->setEmail($address);
        $result->setDescription($description);
        $result->setName($name);
        $result->setMailboxId($id);
        return $result;
    }
}