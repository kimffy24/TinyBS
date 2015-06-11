<?php
namespace TinyBS\SimpleMvc\View;

use TinyBS\BootStrap\BootStrap;

class TinyBsRender {
    const DEFAULT_VIEW_STRATEGY = 'vardump';
    
    static public function render(BootStrap $core, $bootstrapResult=null){
        if($bootstrapResult === null) return;
    	static::renderPrepare($core);
        return (static::$viewFunction)?
            call_user_func_array(
                static::$viewFunction,
                array($bootstrapResult)):
            call_user_func_array(
            	array(StrategyFactory::getInstance(static::$viewStrategy),'render'),
                array($bootstrapResult));
	}
	

	static private $viewFunction = null;
	static private $viewStrategy = self::DEFAULT_VIEW_STRATEGY;
	static private function renderPrepare(BootStrap $core){
		$serviceManager = $core->getServiceManager();
	    //获取命中命名空间
		$matchNamespace = $serviceManager
		      ->get('TinyBS\RouteMatch\Route')
		      ->getMatchNamespace();
		//获取项目配置集合
		$tbsConfig = $serviceManager
		      ->get('config');
		if(isset($tbsConfig['tbs_view']) && isset($tbsConfig['tbs_view'][$matchNamespace])){
		    if(isset(
		        $tbsConfig['tbs_view'][$matchNamespace]['actor'])
		        && 
		        is_callable($tbsConfig['tbs_view'][$matchNamespace]['actor']))
		        static::$viewFunction = $tbsConfig['tbs_view'][$matchNamespace]['actor'];
		    else if(isset($tbsConfig['tbs_view'][$matchNamespace]['strategy']))
		        static::$viewStrategy = $tbsConfig['tbs_view'][$matchNamespace]['strategy'];
		    else static::$viewStrategy=static::DEFAULT_VIEW_STRATEGY;
		}
	}
}