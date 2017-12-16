<?php 
/** 
 * Created by /tools/create-model.php 
 * Time:  2017-12-15 18:44:53
 */ 

namespace Tops\db\model\entity;

class  ProcessLogEntry
{ 
    public $id;
    public $processCode;
    public $posted;
    public $event;
    public $message;
    public $messageType;
    public $detail;

     public function assignFromObject($dto)
     {
         if (isset($dto->id)) {
             $this->id = $dto->id;
         }
         if (isset($dto->processCode)) {
             $this->processCode = $dto->processCode;
         }
         if (isset($dto->posted)) {
             $this->posted = $dto->posted;
         }
         if (isset($dto->event)) {
             $this->event = $dto->event;
         }
         if (isset($dto->message)) {
             $this->message = $dto->message;
         }
         if (isset($dto->messageType)) {
             $this->messageType = $dto->messageType;
         }
     }
}