<?php 
/** 
 * Created by /tools/create-model.php 
 * Time:  2017-12-15 18:44:53
 */ 

namespace Tops\db\model\entity;

class Process  
{ 
    public $id;
    public $code;
    public $name;
    public $description;
    public $paused;
    public $enabled;

     public function assignFromObject($dto) {
    if (isset($dto->id)) {
       $this->id = $dto->id;
    }
    if (isset($dto->code)) {
       $this->code = $dto->code;
    }
    if (isset($dto->name)) {
       $this->name = $dto->name;
    }
    if (isset($dto->description)) {
       $this->description = $dto->description;
    }
    if (isset($dto->paused)) {
       $this->paused = $dto->paused;
    }
    if (isset($dto->enabled)) {
       $this->enabled = $dto->enabled;
    }

} 
}