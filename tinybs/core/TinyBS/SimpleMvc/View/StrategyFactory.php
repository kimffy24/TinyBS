<?php
namespace TinyBS\SimpleMvc\View;

use TinyBS\Utils\RuntimeException;
use TinyBS\SimpleMvc\View\Strategy\JsonStrategy;
use TinyBS\SimpleMvc\View\Strategy\StringStrategy;
use TinyBS\SimpleMvc\View\Strategy\VarDumpStrategy;

class StrategyFactory
{
    /**
     * get an Strategy instance.
     * @param string $name
     * @return \TinyBS\SimpleMvc\View\Strategy\ViewStrategyInterface 
     */
    static public function getInstance($name){
        $targetStrategy = strtolower($name);
        switch($targetStrategy){
        	case 'json':
        		return new  JsonStrategy();
        	case 'string':
        		return new StringStrategy();
        	case 'print_r':
        	case 'vardump':
        		return new VarDumpStrategy();
        	default :
        		throw new RuntimeException(__METHOD__.'() class '.$targetStrategy.' not found');
        }
    }
}