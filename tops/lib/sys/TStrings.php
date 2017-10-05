<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 7/5/2017
 * Time: 6:36 AM
 */

namespace Tops\sys;


class TStrings
{

    const initialCapFormat = 1;
    const wordCapsFormat = 2;
    const keyFormat = 3;
    const dashedFormat = 4;

    public static function convertNameFormat($name,$format) {
        switch ($format) {
            case self::keyFormat :
                $name = trim(strtolower($name));
                $name = str_replace(' ','_',$name);
                return str_replace('-','_',$name);
            case self::initialCapFormat :
                $name = trim(strtolower($name));
                $name = str_replace('_',' ',$name);
                $name = str_replace('-',' ',$name);
                return ucfirst($name);
            case self::wordCapsFormat :
                $name = trim(strtolower($name));
                $name = str_replace('_',' ',$name);
                $name = str_replace('-',' ',$name);
                $result = '';
                $words = explode(' ',str_replace('_',' ',$name));
                foreach ($words as $word) {
                    $result .= ucfirst($word).' ';
                }
                return trim($result);
            case self::dashedFormat :
                $name = trim(strtolower($name));
                $name = str_replace('_','-',$name);
                return str_replace(' ','-',$name);

            default:
                throw new \Exception('Invalid format constant '.$format);
        }
    }

    public static function toCamelCase($s,$delimiter='-') {
        $parts = explode($delimiter,$s);
        $len = sizeof($parts);
        for ($i = 0; $i<$len;$i++) {
            $parts[$i] =  ucfirst($parts[$i]);
        }
        return join('',$parts);
    }

    public static function toTitle($s,$seperator=false) {
        if ($seperator) {
            $s = str_replace($seperator,' ',$s);
        }
        $parts = explode(' ',$s);
        $len = sizeof($parts);
        for ($i = 0; $i<$len;$i++) {
            $part = $parts[$i];
            if ($i > 0 && ($part == 'the' || $part == 'a' || $part == 'of' || $part == 'an' || $part == 'in')) {
                continue;
            }
            $parts[$i] = ucfirst($part);
        }
        return join(' ',$parts);
    }

    /**
     * Convert namespace code, usually for a service call to literal namespace, per TOPS convention
     * Periods replaed by backslashes
     * First part converts to Pascal case
     * Subsequent parts convert to Pascal case if it contains a hyphen, otherwise literal text used.
     *
     * example:
     * two-quakers.testing.services.sub-services > TwoQuakers\testing\services\SubServices
     * two-quakers.Testing.services.sub-services > TwoQuakers\Testing\services\SubServices
     *
     * @param $nscode string
     * @return bool|string
     */
    public static function formatNamespace($nscode)
    {
        if (empty($nscode)) {
            return false;
        }
        $parts = explode('.',$nscode);

        $count = sizeof($parts);
        for ($i=0;$i<$count;$i++) {
            $part = $parts[$i];
            if ($i==0 || strpos($part,'-') !== false) {
                $parts[$i] = self::toCamelCase($part);
            }
        }
        return join('\\',$parts);
    }

}