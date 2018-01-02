<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 12/28/2017
 * Time: 7:02 AM
 */

namespace Tops\sys;


class TDataTransfer
{
    private $data;
    private $object;
    private $dataTypes;

    const dataTypeDate = 'date' ;
    const dataTypeDateTime = 'datetime' ;
    const dataTypeFlag = 'flag' ;
    const dataTypeToday = 'today';
    const dataTypeNow = 'now' ;
    const dataTypeTime = 'time' ;
    const dataTypeDefault = 'any' ;

    // todo: support time data type

    public function __construct($data,$object,$dataTypes=[])
    {
        $this->data = $data;
        $this->object = $object;
        $this->dataTypes = $dataTypes;
    }

    private function getDataType($propertyName)
    {
        if (array_key_exists($propertyName,$this->dataTypes)) {
            return $this->dataTypes[$propertyName];
        }
        return $propertyName == 'active' ? self::dataTypeFlag : self::dataTypeDefault;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getObject()
    {
        return $this->object;
    }

    public function assignProperty($propertyName) {
        $dataType = $this->getDataType($propertyName);
        switch ($dataType) {
            case self::dataTypeDate :
                return $this->assignDate($propertyName);
            case self::dataTypeDateTime :
                return $this->assignDate($propertyName,true);
            case self::dataTypeFlag :
                return $this->assignFlag($propertyName);
            case self::dataTypeToday:
                return $this->assignCurrentDate($propertyName);
            case self::dataTypeNow :
                return $this->assignCurrentDateTime($propertyName);
            case 'time' :
                // todo: support time data type
                return false;
            case 'current-time' :
                // todo: support current-time data type
                return false;
            default :
                return $this->assignValue($propertyName);
        }
    }

    public function assignAll() {
        foreach ($this->data as $key => $value) {
            $this->assignProperty($key);
        }
    }

    public function assignValue($propertyName)
    {
        if (property_exists($this->data, $propertyName) && property_exists($this->object, $propertyName)) {
            $this->object->$propertyName = $this->data->$propertyName;
            return true;
        }
        return false;
    }

    public function assignDate($propertyName, $includeTime = false)
    {
        if (property_exists($this->data, $propertyName) && property_exists($this->object, $propertyName)) {
            $dateValue = TDates::formatMySqlDate($this->data->$propertyName, $includeTime);
            if ($dateValue !== false) {
                $this->object->$propertyName = $dateValue;
                return true;
            }
        }
        return false;
    }

    public function assignCurrentDate( $propertyName,$includeTime=false)
    {
        if (property_exists( $this->object, $propertyName)) {
            $today = Date(
                $includeTime ? TDates::MySqlDateTimeFormat : TDates::MySqlDateFormat
            );
            $this->object->$propertyName = $today;
            return true;
        }
        return false;
    }

    public function assignCurrentDateTime( $propertyName)
    {
        return $this->assignCurrentDate($propertyName,true);
    }

    public function assignActive()
    {
        return $this->assignFlag('active');
    }

    public function assignFlag($propertyName)
    {
        if (property_exists($this->data, $propertyName) && property_exists($this->object, $propertyName)) {
            $this->object->$propertyName = empty($this->data->$propertyName) ? 1 : 0;
            return true;
        }
        return false;
    }

    public function assignDefaultValues($defaults = []) {
        foreach ($defaults as $propertyName => $value) {
            if (!isset($this->object->$propertyName)) {
                switch ($value) {
                    case self::dataTypeNow :
                        $this->assignCurrentDateTime($propertyName);
                        continue;
                    case self::dataTypeToday:
                        $this->assignCurrentDate($propertyName);
                        continue;
                    default:
                        $value = str_replace('`','',$value);
                        $this->object->$propertyName = $value;
                        continue;
                }
            }
        }
    }
}