<?php 
/** 
 * Created by /tools/create-model.php 
 * Time:  2017-12-15 18:44:53
 */ 

namespace Tops\db\model\entity;

use Tops\sys\TDataTransfer;

class Process
{ 
    public $id;
    public $code;
    public $name;
    public $description;
    public $paused; // datetime
    public $enabled; // flag

     public function assignFromObject($dto)
     {
         $dt = new TDataTransfer($dto, $this, [
             'paused' >= TDataTransfer::dataTypeDateTime
         ]);
         $dt->assignAll();
         $dt->assignDefaultValues([
             'id' => 0,
             'enabled' => 1
         ]);
     }
}