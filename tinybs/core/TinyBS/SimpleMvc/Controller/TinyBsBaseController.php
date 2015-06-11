<?php
namespace TinyBS\SimpleMvc\Controller;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class TinyBsBaseController implements ServiceLocatorAwareInterface
{
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator){
        $this->serviceLocator = $serviceLocator;
        return $this;
    }
    
    /**
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator(){
        return $this->serviceLocator;
    }

    /**
     * 遗留函数
     * 同TinyBsBaseController::getControllerPluginsUrl()
     * @return \TinyBS\SimpleMvc\Controller\Plugins\ControllerPluginsUrl
     */
    protected function getViewHelperUrl(){
    	return $this->getControllerPluginsUrl();
    }
    /**
     * 根据路由配置生成路有链接
     * @return \TinyBS\SimpleMvc\Controller\Plugins\ControllerPluginsUrl
     */
    protected function getControllerPluginsUrl(){
    	if(!$this->pluginsUrl)
    		$this->pluginsUrl = $this->getServiceLocator()->get('ControllerPluginsUrl');
    	return $this->pluginsUrl;
    }

    protected function redirect($url){
    	if(is_array($url)){
    		$viewHelperUrl = $this->getViewHelperUrl();
    		$url = call_user_func_array(
        		array($viewHelperUrl, '__invoke'),
        		$url
        	);
    	}
        header("Location: ".$url);
        die;
    }

    private $serviceLocator = null;
    private $pluginsUrl = null;
}