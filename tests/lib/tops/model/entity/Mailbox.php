<?php 
/** 
 * Created by /tools/create-model.php 
 * Time:  2017-09-11 18:24:37
 */ 

namespace Tops\db\model\entity;

class Mailbox  extends \Tops\db\TimeStampedEntity 
{ 
    public $id;
    public $mailboxcode;
    public $address;
    public $displaytext;
    public $description;
    public $active;
    public $public;
} 
