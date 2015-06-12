<?php
/**
 * Created by PhpStorm.
 * User: jiefzz
 * Date: 6/8/15
 * Time: 5:43 PM
 */

namespace TinyBS;

use TinyBS\Utils\RuntimeException;

class Context {

    /**
     * @return \TinyBS\BootStrap\BootStrap
     */
    static function getContext(){
        if(isset($GLOBALS['TinyCore']) && !empty($GLOBALS['TinyCore']))
            return $GLOBALS['TinyCore'];
        throw new RuntimeException("Context couldn\'t get!");
    }

} 