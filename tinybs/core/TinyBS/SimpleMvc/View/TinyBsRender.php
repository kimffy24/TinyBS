<?php
namespace TinyBS\SimpleMvc\View;

use TinyBS\BootStrap\BootStrap;

class TinyBsRender
{
    const DEFAULT_VIEW_STRATEGY = 'VarDumpStrategy';
    static private $viewFunction = null;
    static private $viewStrategy = self::DEFAULT_VIEW_STRATEGY;
    static public function render(BootStrap $core, $bootstrapResult){
    	static::renderPrepare($core);
        $resultArray = null;
        return is_callable(static::$viewFunction)?
            call_user_func_array(static::$viewFunction, $bootstrapResult):
            call_user_func_array(
                array((StrategyFactory::getInstance(static::$viewStrategy)), 'render'),
                array($bootstrapResult));
        ;
	}
	static public function renderPrepare(BootStrap $core){
		$route = $core->getServiceManager()->get('TinyBS\RouteMatch\Route');
		$matchNamespace = $route->getMatchNamespace();
		$tbsConfig = $core->getServiceManager()->get('config');
		if(isset($tbsConfig['tbs_view'])){
		    if(isset($tbsConfig['tbs_view']['strategy']))
		        static::$viewStrategy = $tbsConfig['tbs_view']['strategy'];
		    if(isset($tbsConfig['tbs_view']['actor']) and is_callable($tbsConfig['tbs_view']['actor']))
		        static::$viewFunction = $tbsConfig['tbs_view']['actor'];
		}
	}
}