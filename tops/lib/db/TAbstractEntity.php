<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 1/3/2018
 * Time: 5:33 AM
 */

namespace Tops\db;


use Tops\sys\TDataTransfer;

/**
 * Class TEntityObject
 * @package Tops\db
 */
abstract class TAbstractEntity
{

    public function assignFromObject($dto, $username = 'admin')
    {
        $datatypes = $this->getDtoDataTypes();
        $dt = new TDataTransfer($dto, $this, $datatypes);
        $dt->assignAll();
        $defaults = $this->getDtoDefaults($username);
        $dt->assignDefaultValues($defaults);
        $dt->checkRequiredValues(
            $this->getRequiredFields()
        );
    }

    protected function getRequiredFields() {
        return [];
    }

    protected function getDtoDataTypes()
    {
        return [];
    }

    protected function getDtoDefaults($username = 'system')
    {
        return [];
    }

}