<?php
namespace TinyBS\Http\Utils;

use Exception;

class RequestException extends Exception
{
    public function __construct($message = "Some error occur on constructing TbsRequest Object.", $code=0, $previous=null){
        parent::__construct($message, $code, $previous);
    }
}