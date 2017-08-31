<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 1/23/2015
 * Time: 3:00 PM
 */

namespace Tops\mail;

 use Tops\db\EntityRepositoryFactory;
 use Tops\db\IEntityRepository;

 class TDbMailboxManager implements IMailboxManager {

     /**
      * @var IEntityRepository
      */
     private $repository;

     public function __construct()
     {
         $repository = EntityRepositoryFactory::Get('mailboxes','Tops\\db\\model\\repository');
     }

     /**
      *
      * @param $id
      * @return IMailbox
      */
     public function find($id)
     {
         // TODO: test find() method.
           return $this->repository->get($id);
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
      * @return IMailbox | bool
      */
     public function findByCode($mailboxCode)
     {
         $result = $this->repository->getFirst("box='$mailboxCode'");
         if (empty($result)) {
             return false;
         }
         return TMailbox::Create($result->box,$result->displayText,$result->address,$result->description);

     }

     /**
      * @param null $filter
      * @return IMailbox[]
      */
     public function getMailboxes($filter = null)
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
         // TODO: Implement addMailbox() method.
     }

     /**
      * @param IMailbox $mailbox
      * @return int
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
         // TODO: Implement createMailbox() method.
     }

     public function saveChanges()
     {
         // TODO: Implement saveChanges() method.
     }
 }