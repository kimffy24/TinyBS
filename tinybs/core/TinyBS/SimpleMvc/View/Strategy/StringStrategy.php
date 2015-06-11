<?php
namespace TinyBS\SimpleMvc\View\Strategy;

use RuntimeException;

class StringStrategy implements ViewStrategyInterface {
    public function render($resultArray) {
        if(is_string($resultArray)){
    		header("Content-Type:text/html;charset=utf-8");
    		echo $resultArray;
        } else 
        	throw new RuntimeException("Use a StringStrategy but not string result given!");
    }

}