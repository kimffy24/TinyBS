<?php
namespace TinyBS\SimpleMvc\Controller;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\Url;

abstract class TinyBsBaseController implements ServiceLocatorAwareInterface
{
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator){
        $this->serviceLocator = $serviceLocator;
        return $this;
    }
    public function getServiceLocator(){
        return $this->serviceLocator;
    }

    /**
     * @return \Zend\View\Helper\Url
     */
    protected function getViewHelperUrl(){
    	if(!$this->viewHelperUrl)
    		$this->viewHelperUrl = $this->getServiceLocator()->get('TinyBS\View\Helper\Url');
    	return $this->viewHelperUrl;
    }

    protected function redirect($url){
        header("Location: "+$url);
        exit;
    }

    private $serviceLocator = null;
    private $viewHelperUrl = null;
}