<?php
namespace TinyBS\SimpleMvc\View\Strategy;

class VarDumpStrategy implements ViewStrategyInterface
{
    public function render($resultArray){
        //header('Content-type: application/json');
        echo var_dump($resultArray);
        return $resultArray;
    }
}