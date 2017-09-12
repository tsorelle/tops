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
 use Tops\db\IMailboxesRepository;

 class TDbMailboxManager implements IMailboxManager {

     /**
      * @var IMailboxesRepository
      */
     private $repository;

     public function __construct()
     {
         $this->repository = EntityRepositoryFactory::Get('mailboxes','Tops\\db\\model\\repository');
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
         $result = $this->repository->getEntity($mailboxCode);
         if (empty($result)) {
             return false;
         }
         return $result;
     }

     /**
      * @param null $filter
      * @return \stdClass[]
      */
     public function getMailboxes($showAll = false)
     {
         return $this->repository->getMailboxList($showAll);

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