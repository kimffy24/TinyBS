<?php
namespace TinyBS\SimpleMvc\View;

use Exception;

class StrategyFactory
{
    const JSON_DEBUG_STRATEGY = 'VarDumpStrategy';
    const JSON_STRATEGY = 'JsonStrategy';
    const STRING_STRATEGY = 'StringStrategy';
    /**
     * get an instance.
     * @param string $name
     * @return \TinyBS\SimpleMvc\View\Strategy\ViewStrategyInterface 
     */
    static public function getInstance($name){
        $targetStrategy = __NAMESPACE__.'\\Strategy\\'.$name;
        if(class_exists($targetStrategy))
            return new $targetStrategy();
        throw new Exception(__METHOD__.'() class '.$targetStrategy.' not found');
    }
}