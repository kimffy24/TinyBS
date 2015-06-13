<?php
/**
 * Created by PhpStorm.
 * User: jiefzz
 * Date: 6/8/15
 * Time: 3:45 PM
 */

namespace TinyBS\Utils;


class EnvironmentTools {

    static function topEnvironmentPrepare(){
        self::prepareOutputBufferFunction();
    }

    static public function registerShutdown($callback){
        if(is_callable($callback))
            register_shutdown_function($callback);
    }


    static private function prepareOutputBufferFunction(){
        // if output buffer is not supported, then end invoking!
        if(!function_exists('ob_get_level'))
            return;

        $obStatus = ob_get_level();
        // if $obStatus > 0
        //      echo "there some output was clean!" >> logFile
        if($obStatus>0)
            ob_end_clean();

        // Whether the function is on top level or not, clean it away
        // Do not use gzip compress, we wish it will be use on the web server, like nginx or apache
        ob_start();
        self::registerShutdown('ob_end_flush');
    }
}