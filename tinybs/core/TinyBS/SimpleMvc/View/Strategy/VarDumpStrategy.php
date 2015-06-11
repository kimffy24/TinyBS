<?php
namespace TinyBS\SimpleMvc\View\Strategy;

class VarDumpStrategy implements ViewStrategyInterface
{
    public function render($resultArray) {
    	header("Content-Type:text/html;charset=utf-8");
        var_dump($resultArray);
        return $resultArray;
    }
}