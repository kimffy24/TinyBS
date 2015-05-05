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
    public function getServiceLocator(){
        return $this->serviceLocator;
    }
    
    protected function getViewHeplerUrl(){
    	if(!$this->viewHelperUrl)
    		$this->viewHelperUrl = $this->getServiceLocator()->get('TinyBS\View\Helper\Url');
    	return $this->viewHelperUrl;
    }
    private $serviceLocator = null;
    private $viewHelperUrl = null;
}