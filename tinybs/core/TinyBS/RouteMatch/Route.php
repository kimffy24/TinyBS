<?php
namespace TinyBS\RouteMatch;

use TinyBS\BootStrap\BootStrap;
use TinyBS\SimpleMvc\Controller\BaseController;

class Route {
    static private $matchController;
    //static private $matchModule;
	static public function loadModuleRoute(BootStrap $core){
		//$composerAutoloader = ComposerAutoloader::getComposerAutoloader();
		//if(!$composerAutoloader)
		//    throw new \RuntimeException('At '.__METHOD__.' : Composer\Autoload not load!');
		$route = $core->getServiceManager()->get('route');
		$routeMatch = $route->match($core->getServiceManager()->get('Request'));
		if(!$routeMatch)
		    throw new \RuntimeException('At '.__METHOD__.' : There no match modules!');
		$core->getServiceManager()->setService('RouteMatch', $routeMatch);
		$routeMatchParams = $routeMatch->getParams();
		if(!isset($routeMatchParams['__NAMESPACE__']) and !isset($routeMatchParams['controller']))
		    throw new \RuntimeException('At '.__METHOD__.' : There no controller at match modules!');
		$targetController = isset($routeMatchParams['__NAMESPACE__'])?
            $routeMatchParams['__NAMESPACE__'].'\\'.$routeMatchParams['controller']:
            $routeMatchParams['controller']
        ;
        $matchNamespace = substr($targetController, 0, strpos($targetController, '\\'));
		//$composerAutoloader->set($matchNamespace,MODULELOCATION);
        BootStrap::loadSpecialModule($matchNamespace);
		if(class_exists($targetController)){
		    $aimController = new $targetController();
		    if(($aimController instanceof BaseController) or is_callable($aimController, 'setServiceLocator'))
		        $aimController->setServiceLocator($core->getServiceManager());
            $core->getServiceManager()->setService($targetController, $aimController);
            static::$matchController = $aimController;
		} else 
		    throw new \RuntimeException('At '.__METHOD__.' : There match module doesn\'t exist!');
		return ;
	}
	static public function dispatch(BootStrap $core){
	    $matchMatchParamArray = $core->getServiceManager()->get('RouteMatch')->getParams();
	    //$matchController = (isset($matchMatchParamArray['__NAMESPACE__']))?
	    //   $matchMatchParamArray['__NAMESPACE__'].'\\'.$matchMatchParamArray['controller']:
	    //   $matchMatchParamArray['controller'];
	    $matchAction = $matchMatchParamArray['action'].'Action';
	    $bootstrapResult = call_user_func_array(
	        array(static::$matchController, $matchAction),
	        array()
	    );
	    return $bootstrapResult;
	}
}