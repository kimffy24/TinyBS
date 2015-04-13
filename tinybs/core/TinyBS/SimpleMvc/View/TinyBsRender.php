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
        return is_callable(static::$viewFunction)?
            call_user_func_array(
                static::$viewFunction,
                array($bootstrapResult)):
            call_user_func_array(
                array((StrategyFactory::getInstance(static::$viewStrategy)), 'render'),
                array($bootstrapResult));
        ;
	}
	static public function renderPrepare(BootStrap $core){
	    //获取命中命名空间
		$matchNamespace = $core
		      ->getServiceManager()
		      ->get('TinyBS\RouteMatch\Route')
		      ->getMatchNamespace();
		//获取项目配置集合
		$tbsConfig = $core
		      ->getServiceManager()
		      ->get('config');
		if(isset($tbsConfig['tbs_view']) && isset($tbsConfig['tbs_view'][$matchNamespace])){
		    if(isset(
		        $tbsConfig['tbs_view'][$matchNamespace]['actor'])
		        && 
		        is_callable($tbsConfig['tbs_view'][$matchNamespace]['actor']))
		        static::$viewFunction = $tbsConfig['tbs_view'][$matchNamespace]['actor'];
		    if(isset($tbsConfig['tbs_view'][$matchNamespace]['strategy']))
		        static::$viewStrategy = $tbsConfig['tbs_view'][$matchNamespace]['strategy'];
		}
	}
}