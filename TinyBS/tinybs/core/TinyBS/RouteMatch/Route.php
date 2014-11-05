<?php
namespace TinyBS\RouteMatch;

use TinyBS\BootStrap\BootStrap;

class Route {
	static function loadModuleRoute(BootStrap $core){
		$route = $core->getServiceManager()->get('route');
		var_dump($route->match($core->getServiceManager()->get('Request')));die();
	}
}