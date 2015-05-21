<?php
namespace TinyBS\RouteMatch;

use TinyBS\BootStrap\BootStrap;
use TinyBS\SimpleMvc\Utils\ModuleInitializationInterface;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use TinyBS\SimpleMvc\Controller\Utils\PreDispatchInterface;

/**
 * TinyBS Route Match control class。
 * It is different from the Route compoment of ZendFramework2
 * @author JiefzzLon
 *
 */
class Route {
    public function __construct(BootStrap $core){
    	$this->core = $core;
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
		if(/*!isset($routeMatchParams['__NAMESPACE__']) and */!isset($routeMatchParams['controller']))
		    throw new \RuntimeException('At '.__METHOD__.' : There no controller at match modules!');
		$targetController = isset($routeMatchParams['__NAMESPACE__'])?
            $routeMatchParams['__NAMESPACE__'].'\\'.$routeMatchParams['controller']:
            $routeMatchParams['controller']
        ;
        $this->matchNamespace = substr($targetController, 0, strpos($targetController, '\\'));
        BootStrap::loadSpecialModule($this->matchNamespace);
		if(class_exists($targetController)){
		    $targetControllerObject = new $targetController();
		    if(($targetControllerObject instanceof ServiceLocatorAwareInterface) or is_callable($targetControllerObject, 'setServiceLocator'))
		        $targetControllerObject->setServiceLocator($core->getServiceManager());
            $core->getServiceManager()->setService($targetController, $targetControllerObject);
            $this->matchController = $targetController;
            $this->matchControllerObject = $targetControllerObject;
            
            if(is_callable(array(
            				$this->matchControllerObject,
            				$routeMatchParams['action'].'Action'
            		)))
            	$this->matchAction = $routeMatchParams['action'];
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
		$this->preDispatch();
		//$this -> configureViewHelperUrl();
		
		//$core = $this->getBoostrapObject();
	    //$matchMatchParamArray = $core->getServiceManager()->get('RouteMatch')->getParams();
	    return call_user_func_array(
	        array($this->matchControllerObject, $this->matchAction/*$matchMatchParamArray['action']*/.'Action'),
	        array()
	    );
	}
	
	/**
	 * 调用模块自身的初始化函数（假如存在）
	 * 调用目标控制器的初始化函数（假如存在）
	 */
	private function preDispatch(){
		$serviceManager = $this->getBoostrapObject()->getServiceManager();
		$moduleInitializationClass = $this->getMatchNamespace().'\\ModuleInitialization';
		if(class_exists($moduleInitializationClass)){
			//模块初始化对象时默认传递第一个参数为ServiceManager
			$moduleInitializationObject = new $moduleInitializationClass($serviceManager);
			
			//注册到服务管理器中，并设置别名
			$serviceManager->setService($moduleInitializationClass, $moduleInitializationObject);
			$serviceManager->setAlias('ModuleInitialization', $moduleInitializationClass);
			
			//具备服务定位器释义接口时，调用服务定位器注入函数
			if($moduleInitializationObject instanceof ServiceLocatorAwareInterface){
				$moduleInitializationObject->setServiceLocator($serviceManager);
			}
			
			//若果扩展自ModuleInitializationInterface，执行扩展的方法！
			if($moduleInitializationObject instanceof ModuleInitializationInterface){
				call_user_func_array(
					array($moduleInitializationObject,'initModule'),
					array()
				);
			}
		}
		
		//若果目标控制器扩展了PreDispatchInterface，则调用扩展方法
		if($this->matchControllerObject instanceof PreDispatchInterface){
			call_user_func_array(
				array($this->matchControllerObject,'_preDispatch'),
				array()
			);
		}
	}
	
	
	/*private function configureViewHelperUrl(){
		// since every is ok ,
		// we regist url view helper
		//
		$serviceManager = $this->getBoostrapObject()->getServiceManager();
		$route = $serviceManager->get('route');
		$routeMatch = $serviceManager->get('RouteMatch');
		$viewHelperUrl = new Url();
		$viewHelperUrl->setRouter($route);
		$viewHelperUrl->setRouteMatch($routeMatch);
		$serviceManager->setService('TinyBS\View\Helper\Url', $viewHelperUrl);
	}*/
	private function getBoostrapObject(){
		return $this->core;
	}
	private $core;
    private $matchController;
    private $matchNamespace;
    private $matchAction;
    
    /**
     * there no method to get this object!!
     * @var \TinyBS\SimpleMvc\Controller\TinyBsBaseController
     */
    private $matchControllerObject;
}