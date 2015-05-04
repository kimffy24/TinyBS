<?php
namespace TinyBS\RouteMatch;

use TinyBS\BootStrap\BootStrap;
use TinyBS\SimpleMvc\Controller\TinyBsBaseController;

use Zend\View\Helper\Url;

/**
 * TinyBS Route Match control class。
 * It is different from the Route compoment of ZendFramework2
 * @author JiefzzLon
 *
 */
class Route {
	private $core;
    private $matchController;
    private $matchNamespace;
    
    /**
     * 强依赖于一个Tbs核心对象
     */
    public function __construct(BootStrap $core){
    	$this->core = $core;
		// 往核心框架中注册自己，以便能被核心框架中的其他对象能够找到自己。
		// @todo 暂时没考虑出现重复注册的问题
		$core->getServiceManager()->setService(__CLASS__, $this);
    }
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

    /**
     * found the match module(namespace) and match controller
     * @param BootStrap $core
     * @throws \RuntimeException
     */
	public function loadModuleRoute(){
		$core = $this->getBoostrapObject();
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
		
		return $this;
	}
	
	/**
	 * 触发命中的控制器方法
	 * @param BootStrap $core
	 * @return mixed
	 */
	public function dispatch(){
		$this -> configureViewHelperUrl();
		
		$core = $this->getBoostrapObject();
	    $matchMatchParamArray = $core->getServiceManager()->get('RouteMatch')->getParams();
	    return call_user_func_array(
	        array($this->matchController, $matchMatchParamArray['action'].'Action'),
	        array()
	    );
	}
	
	private function getBoostrapObject(){
		return $this->core;
	}
	
	private function configureViewHelperUrl(){
		// since every is ok ,
		// we regist url view helper
		//
		$route = $this->getBoostrapObject()->getServiceManager()->get('route');
		$routeMatch = $route->match($this->getBoostrapObject()->getServiceManager()->get('Request'));
		$viewHelperUrl = new Url();
		$viewHelperUrl->setRouter($route);
		$viewHelperUrl->setRouteMatch($routeMatch);
		$this->getBoostrapObject()->getServiceManager()->setService('TinyBS\View\Helper\Url', $viewHelperUrl);
	}
}