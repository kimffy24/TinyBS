<?php
namespace TinyBS\RouteMatch;

use TinyBS\BootStrap\BootStrap;
use TinyBS\SimpleMvc\Controller\TinyBsBaseController;

/**
 * TinyBS Route Match control class。
 * @author JiefzzLon
 *
 */
class Route {
    private $matchController;
    private $matchNamespace;
    /**
     * return the match Controller name
     * @return the $matchController
     */
    public function getMatchController()
    {
        return $this->matchController;
    }

	/**
	 * return the mathc Namespace name
     * @return the $matchNamespace
     */
    public function getMatchNamespace()
    {
        return $this->matchNamespace;
    }

	public function loadModuleRoute(BootStrap $core){
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
        $this->matchNamespace = substr($targetController, 0, strpos($targetController, '\\'));
        BootStrap::loadSpecialModule($this->matchNamespace);
		if(class_exists($targetController)){
		    $aimController = new $targetController();
		    if(($aimController instanceof TinyBsBaseController) or is_callable($aimController, 'setServiceLocator'))
		        $aimController->setServiceLocator($core->getServiceManager());
            $core->getServiceManager()->setService($targetController, $aimController);
            $this->matchController = $aimController;
		} else 
		    throw new \RuntimeException('At '.__METHOD__.' : There match module doesn\'t exist!');
		return ;
	}
	public function dispatch(BootStrap $core){
	    $core->getServiceManager()->setService(__CLASS__, $this);
	    $matchMatchParamArray = $core->getServiceManager()->get('RouteMatch')->getParams();
	    return call_user_func_array(
	        array($this->matchController, $matchMatchParamArray['action'].'Action'),
	        array()
	    );
	}
	
}