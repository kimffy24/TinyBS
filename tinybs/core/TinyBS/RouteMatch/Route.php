<?php
namespace TinyBS\RouteMatch;

use TinyBS\BootStrap\BootStrap;
use TinyBS\BootStrap\ComposerAutoloader;
use Composer;

class Route {
	static function loadModuleRoute(BootStrap $core){
		$composerAutoloader = ComposerAutoloader::getComposerAutoloader();
		if(!$composerAutoloader)
		    throw new \RuntimeException('At '.__METHOD__.' : Composer\Autoload not load!');
		$route = $core->getServiceManager()->get('route');
		$routeMatch = $route->match($core->getServiceManager()->get('Request'));
		if(!$routeMatch)
		    throw new \RuntimeException('At '.__METHOD__.' : There no match modules!');
		$core->getServiceManager()->setService('RouteMatch', $routeMatch);
		$routeMatchParams = $routeMatch->getParams();
		if(!isset($routeMatchParams['__NAMESPACE__']) and !isset($routeMatchParams['controller']))
		    throw new \RuntimeException('At '.__METHOD__.' : There no controller at match modules!');
		$matchNamespace = (isset($routeMatchParams['__NAMESPACE__']))?$routeMatchParams['__NAMESPACE__']:$routeMatchParams['controller'];
		$matchNamespace = substr($matchNamespace, 0, strpos($matchNamespace, '\\'));
		$composerAutoloader->set($matchNamespace,MODULELOCATION);
		return $routeMatch;
	}
	static public function dispatch(BootStrap $core){
	    $matchMatchParamArray = $core->getServiceManager()->get('RouteMatch')->getParams();
	    $matchController = (isset($matchMatchParamArray['__NAMESPACE__']))?
	       $matchMatchParamArray['__NAMESPACE__'].'\\'.$matchMatchParamArray['controller']:
	       $matchMatchParamArray['controller'];
	    $matchAction = $matchMatchParamArray['action'].'Action';
	    return call_user_func_array(array($core->getServiceManager()->get($matchController), $matchAction), array());
	}
}