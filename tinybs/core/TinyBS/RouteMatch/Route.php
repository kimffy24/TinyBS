<?php
namespace TinyBS\RouteMatch;

use RuntimeException;

use TinyBS\BootStrap\BootStrap;
use TinyBS\SimpleMvc\Utils\ModuleInitializationInterface;
use TinyBS\SimpleMvc\Controller\Utils\PreDispatchInterface;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

/**
 * TinyBS Route Match control class。
 * It is different from the Route compoment of ZendFramework2
 * @author JiefzzLon
 *
 */
class Route {
    public function __construct(BootStrap $core) {
    	$this->core = $core;
		$core->getServiceManager()->setService(__CLASS__, $this);
    }
    /**
     * return the match Controller name
     * @return the $matchController
     */
    public function getMatchController() {
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
	 * return the mathc Action name
     * @return the $matchNamespace
     */
    public function getMatchAction(){
    	return $this->matchAction;
    }

    /**
     * found the match module(namespace) and match controller
     * and construct the target Controller object 
     * and try to inject the ServiceManager into it
     * @param \TinyBs\BootStrap\BootStrap $core
     * @throws \RuntimeException
     */
	public function loadModuleRoute(){
		$core = $this->getBoostrapObject();
		$serviceManager = $core->getServiceManager();
		
		$route = $serviceManager->get('route');
		$routeMatch = $route->match($serviceManager->get('Request'));
		if(!$routeMatch)
		    throw new RuntimeException('At '.__METHOD__.' : There no match modules!');
		$serviceManager->setService('RouteMatch', $routeMatch);
		$routeMatchParams = $routeMatch->getParams();
		if(!isset($routeMatchParams['controller']))
		    throw new RuntimeException('At '.__METHOD__.' : There no match controller at this module!');
		$targetController = isset($routeMatchParams['__NAMESPACE__'])?
            $routeMatchParams['__NAMESPACE__'].'\\'.$routeMatchParams['controller']:
            $routeMatchParams['controller']
        ;
        $this->matchNamespace = substr($targetController, 0, strpos($targetController, '\\'));
        BootStrap::loadSpecialModule($this->matchNamespace);
		if(class_exists($targetController)){
		    $this->matchControllerObject = $targetControllerObject = new $targetController();
            $this->matchController = $targetController;
            $serviceManager->setService($targetController, $targetControllerObject);
            
		    if(($targetControllerObject instanceof ServiceLocatorAwareInterface))
		        $targetControllerObject->setServiceLocator($serviceManager);
            
            if(is_callable(array(
            				$targetControllerObject,
            				$routeMatchParams['action'].'Action'
            		)))
            	$this->matchAction = $routeMatchParams['action'];
            else 
		    	throw new RuntimeException('At '.__METHOD__.' : There no match method is this module!');
		} else 
		    throw new RuntimeException('At '.__METHOD__.' : There match controller doesn\'t exist!');
		
		return $this;
	}
	
	/**
	 * 触发命中的控制器方法
	 * @param BootStrap $core
	 * @return mixed
	 */
	public function dispatch(){
		$this->preDispatch();
		return call_user_func_array(
	        array($this->matchControllerObject, $this->matchAction.'Action'),
	        array()
	    );
	}
	
	/**
	 * 调用模块自身的初始化函数（假如存在）
	 * 调用目标控制器的初始化函数（假如存在）
	 */
	private function preDispatch(){
		$moduleInitializationClass = $this->getMatchNamespace().'\\ModuleInitialization';
		if(class_exists($moduleInitializationClass)){
			$serviceManager = $this->getBoostrapObject()->getServiceManager();
			$moduleInitializationObject = new $moduleInitializationClass();
			
			//regist into ServiceManager object
			$serviceManager->setService($moduleInitializationClass, $moduleInitializationObject);
			$serviceManager->setAlias('ModuleInitialization', $moduleInitializationClass);
			
			if($moduleInitializationObject instanceof ServiceManagerAwareInterface){
				$moduleInitializationObject->setServiceManager($serviceManager);
			}
			
			//if the moduleInitializationObject implement ModuleInitializationInterface, then invoke method 'initModule'
			if($moduleInitializationObject instanceof ModuleInitializationInterface){
				call_user_func_array(
					array($moduleInitializationObject,'initModule'),
					array()
				);
			}
		}
		
		//if the matchController implement PreDispatchInterface, invoke the method '_preDispatch'
		if($this->matchControllerObject instanceof PreDispatchInterface){
			call_user_func_array(
				array($this->matchControllerObject,'_preDispatch'),
				array()
			);
		}
	}
	
	/**
	 * get the BootStrap object
	 * @return \TinyBS\BootStrap\BootStrap;
	 */
	private function getBoostrapObject(){
		return $this->core;
	}
	
	/**
	 * @var \TinyBS\BootStrap\BootStrap;
	 */
	private $core;
    /**
     * @var string
     */
    private $matchController = null;
    /**
     * @var string
     */
    private $matchNamespace = null;
    /**
     * @var string
     */
    private $matchAction = null;
    
    /**
     * there no method to get this object!!
     * @var \TinyBS\SimpleMvc\Controller\TinyBsBaseController
     */
    private $matchControllerObject = null;
}