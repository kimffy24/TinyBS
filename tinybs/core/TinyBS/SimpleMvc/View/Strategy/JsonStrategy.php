<?php
namespace TinyBS\SimpleMvc\View\Strategy;

class JsonStrategy implements ViewStrategyInterface
{
    public function render($resultArray){
        header('Content-type: application/json');
        echo json_encode($resultArray);
        return $resultArray;
    }
}