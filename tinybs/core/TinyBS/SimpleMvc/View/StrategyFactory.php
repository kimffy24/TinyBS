<?php
namespace TinyBS\SimpleMvc\View;

class StrategyFactory
{
    const JSON_DEBUG_STRATEGY = 'JsonDebugStrategy';
    const JSON_STRATEGY = 'JsonStrategy';
    const STRING_STRATEGY = 'StringStrategy';
    /**
     * get an instance.
     * @param string $name
     * @return \TinyBS\SimpleMvc\View\Strategy\ViewStrategyInterface 
     */
    public function getInstance($name){
        switch($name){
            case self::JSON_DEBUG_STRATEGY:
            case self::JSON_STRATEGY:
            case self::STRING_STRATEGY:
                $targetClassName = __NAMESPACE__.'\\Strategy\\'.$name;
            default:
                $targetClassName = __NAMESPACE__.'\\Strategy\\'.self::JSON_DEBUG_STRATEGY;
        }
        return new $targetClassName();
    }
}