<?php

namespace TinyBS\SimpleMvc\Controller\Plugins;

use Zend\View\Helper\Url;
use Zend\ServiceManager\ServiceManager;

class ControllerPluginsUrl extends Url{
	public function __construct(ServiceManager $sm){
		//$this->serviceManager = $sm;
		$route = $sm->get('route');
		$routeMatch = $sm->get('RouteMatch');
		$this->setRouter($route);
		$this->setRouteMatch($routeMatch);
		//$sm->setService(__CLASS__, $this);
		//$sm->setAlias("ControllerPluginsUrl", __CLASS__);
	}
	
	//private $serviceManager;
}