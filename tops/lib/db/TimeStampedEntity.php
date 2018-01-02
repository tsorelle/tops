<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 5/6/2017
 * Time: 8:49 AM
 */

namespace Tops\db;

use Tops\sys\TDataTransfer;

class TimeStampedEntity
{
    public $createdby;
    public $createdon;
    public $changedby;
    public $changedon;

    public function setCreateTime($userName = 'admin')
    {
        $today = new \DateTime();
        $date = $today->format('Y-m-d H:i:s');
        $this->createdby = $userName;
        $this->createdon = $date;
        $this->changedby = $userName;
        $this->changedon = $date;
    }

    public function setUpdateTime($userName = 'admin')
    {
        $today = new \DateTime();
        $date = $today->format('Y-m-d H:i:s');
        $this->changedby = $userName;
        $this->changedon = $date;
    }

    public function assignFromObject($dto,$username='admin')
    {
        $datatypes = $this->getDtoDataTypes();
        $dt = new TDataTransfer($dto, $this, $datatypes);
        $dt->assignAll();
        $defaults = $this->getDtoDefaults($username);
        $dt->assignDefaultValues($defaults);
    }


    public function getDtoDataTypes() {
        return [
            'createdon' => TDataTransfer::dataTypeDateTime,
            'changedon' => TDataTransfer::dataTypeDateTime
        ];
        }

    public function getDtoDefaults($username='system') {
        return [
            'id' => 0,
            'createdby' => $username,
            'changedby' => $username,
            'createdon' => TDataTransfer::dataTypeNow,
            'changedon' => TDataTransfer::dataTypeNow
        ];
    }
}