<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 11/6/2017
 * Time: 9:31 AM
 */

namespace Tops\sys;

/**
 * Class TKeyValuePair
 *
 * Create DTO objects to match Typscript definition: IKeyValuePair
 *
 * @package Tops\sys
 */
class TKeyValuePair
{
    public static function Create($key,$value)
    {
        $result = new \stdClass();
        $result->Key = $key;
        $result->Value = $value;
        return $result;
    }

    public static function CreateArray(array $a, $replacements = null) {
        $result = array();
        foreach ($a as $key => $value) {
            foreach ($replacements as $search => $replace) {
                $value = str_replace($search,$replace,$value);
            }
            $result[] = self::Create($key,$value);
        }
        return $result;
    }

    public static function ToArray(array $objects ) {
        $result = array();
        foreach ($objects as $kv) {
            $result[$kv->Key] = $kv->Value;
        }
        return $result;
    }
}