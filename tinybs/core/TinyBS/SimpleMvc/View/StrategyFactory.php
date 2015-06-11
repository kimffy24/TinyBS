<?php
namespace TinyBS\SimpleMvc\View;

use Exception;

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
        		return new \TinyBS\SimpleMvc\View\Strategy\JsonStrategy();
        	case 'string':
        		return new \TinyBS\SimpleMvc\View\Strategy\StringStrategy();
        	case 'print_r':
        	case 'vardump':
        		return new \TinyBS\SimpleMvc\View\Strategy\VarDumpStrategy();
        	default :
        		throw new Exception(__METHOD__.'() class '.$targetStrategy.' not found');
        }
    }
}