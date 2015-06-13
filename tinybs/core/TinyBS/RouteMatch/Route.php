<?php
namespace TinyBS\RouteMatch;

use TinyBS\Utils\RuntimeException;
use TinyBS\BootStrap\BootStrap;
use TinyBS\BootStrap\ServiceManagerUtils;
use TinyBS\SimpleMvc\Utils\ModuleInitializationInterface;
use TinyBS\SimpleMvc\Controller\Utils\PreDispatchInterface;
use TinyBS\SimpleMvc\SpecialServiceManagerConfigInterface;

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
		$targetControllerObject = null;
		$targetController = null;
		
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
        BootStrap::loadSpecialModuleIntoComposerAutoloader($this->matchNamespace);
		if(class_exists($targetController)){
		    $this->matchControllerObject = $targetControllerObject = new $targetController();
            $this->matchController = $targetController;
            if(is_callable(array(
            				$targetControllerObject,
            				$routeMatchParams['action'].'Action')))
            	$this->matchAction = $routeMatchParams['action'];
            else 
		    	throw new RuntimeException('At '.__METHOD__.' : There no match method is this module!');
		} else 
		    throw new RuntimeException('At '.__METHOD__.' : There match controller doesn\'t exist!');

		$serviceManager->setService($targetController, $targetControllerObject);
		$serviceManager->setAlias('matchController', $targetController);
		return $this;
	}
	
	/**
	 * dispatch
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
	 * Inject ServiceManager object into the match Controller object and ModuleInitialization object (if exist)!
	 * and try to do initialization on the match modules <br />
	 * and try to load the special setting to ServiceManager <br />
	 * and try to invoke _preDispath method on the match Controller object <br />
	 */
	private function preDispatch(){
		$serviceManager = $this->getBoostrapObject()->getServiceManager();
		
		$moduleInitializationClass = $this->getMatchNamespace().'\\ModuleInitialization';
		if(class_exists($moduleInitializationClass)){
			$moduleInitializationObject = new $moduleInitializationClass();
			
			//regist into ServiceManager object
			$serviceManager->setService($moduleInitializationClass, $moduleInitializationObject);
			$serviceManager->setAlias('ModuleInitialization', $moduleInitializationClass);
			
			if($moduleInitializationObject instanceof ServiceManagerAwareInterface){
				$moduleInitializationObject->setServiceManager($serviceManager);
			}
			
			//if the moduleInitializationObject implement ModuleInitializationInterface, then invoke method 'initModule'
			if($moduleInitializationObject instanceof ModuleInitializationInterface)
				$moduleInitializationObject->initModule();
			
			if($moduleInitializationObject instanceof SpecialServiceManagerConfigInterface)
				ServiceManagerUtils::configServiceManager(
					$serviceManager,
					$moduleInitializationObject->getServiceManagerConfigArray());
		}
		

		$targetControllerObject = $this->matchControllerObject;
		if(($targetControllerObject instanceof ServiceLocatorAwareInterface))
			$targetControllerObject->setServiceLocator($serviceManager);
		
		//if the matchController implement PreDispatchInterface, invoke the method '_preDispatch'
		if($targetControllerObject instanceof PreDispatchInterface)
			$targetControllerObject->_preDispatch();
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