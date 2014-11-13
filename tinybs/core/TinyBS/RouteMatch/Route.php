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
		return $routeMatch;
	}
}