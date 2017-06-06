<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 6/6/2017
 * Time: 6:41 AM
 */

namespace TwoQuakers\testing;

class TestErrorLogger
{
    public function log(\Exception $ex) {
        print "Logged error: ".$ex->getMessage()."\n";
        return 'Log #124';
    }
}