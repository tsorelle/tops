<?php 
/** 
 * Created by /tools/create-model.php 
 * Time:  2018-06-05 15:23:26
 */ 

namespace Tops\db\model\entity;

class EntityPropertyDefinition  extends \Tops\db\TAbstractEntity
{ 
    public $id;
    public $entityCode;
    public $key;
    public $order;
    public $valueCount;
    public $lookup;
    public $required;
    public $defaultValue;
    public $datatype;
}
