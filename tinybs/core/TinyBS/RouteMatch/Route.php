<?php
namespace TinyBS\RouteMatch;

use TinyBS\BootStrap\BootStrap;

class Route {
	static function loadModuleRoute(BootStrap $core){
		$route = $core->getServiceManager()->get('route');
		return $routeMatch = $route->match($core->getServiceManager()->get('Request'));
	}
}