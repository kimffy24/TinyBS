<?php
namespace TinyBS\Http\Request\Post;

class Json
{
    public function __construct(){
        $_PUT = array();
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            parse_str(file_get_contents('php://input'), $_PUT);
        }
    }
}