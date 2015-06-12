<?php
/**
 * Created by PhpStorm.
 * User: jiefzz
 * Date: 6/8/15
 * Time: 5:49 PM
 */

namespace TinyBS\Utils;

use RuntimeException as SystemRuntimeException;

class RuntimeException extends SystemRuntimeException{
    public function __construct($str="Some fatal exception throw!", $code=-1, $previous=null){
        parent::__construct($str, $code, $previous);
    }
} 