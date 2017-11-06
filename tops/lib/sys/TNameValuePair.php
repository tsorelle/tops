<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 11/6/2017
 * Time: 9:42 AM
 */

namespace Tops\sys;

/**
 * Class TNameValuePair
 *
 * Create DTO objects to match Typscript definition: IKameValuePair
 *
 *
 * @package Tops\sys
 */
class TNameValuePair
{
    public static function Create($name,$value)
    {
        $result = new \stdClass();
        $result->Name = $name;
        $result->Value = $value;
        return $result;
    }

    public static function CreateArray(array $a, $replacements = null) {
        $result = array();
        foreach ($a as $name => $value) {
            foreach ($replacements as $search => $replace) {
                $value = str_replace($search,$replace,$value);
            }
            $result[] = self::Create($name,$value);
        }
        return $result;
    }

    public static function ToArray(array $objects ) {
        $result = array();
        foreach ($objects as $kv) {
            $result[$kv->Name] = $kv->Value;
        }
        return $result;
    }

}