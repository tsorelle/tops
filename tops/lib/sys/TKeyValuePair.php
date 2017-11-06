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

    public static function CreateArray(array $a, $encodeJson=false, $replacements = null) {
        $result = array();
        foreach ($a as $key => $value) {
            foreach ($replacements as $search => $replace) {
                $value = str_replace($search,$replace,$value);
            }
            $kv = self::Create($key,$value);
            if ($encodeJson) {
                $kv = json_encode($kv);
            }
            $result[] = $kv;
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

    public static function CreateCookie(array $a,$cookieName) {
        $encoded = self::CreateArray($a,true,array('+' => '[plus]'));
        $cookie =  '['. join(',',$encoded).']';
        setcookie($cookieName,$cookie);
    }
}