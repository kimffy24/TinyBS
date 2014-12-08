<?php
namespace TinyBS\SimpleMvc\Controller;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class TinyBsBaseController implements ServiceLocatorAwareInterface
{
    private $serviceLocator = null;
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator){
        $this->serviceLocator = $serviceLocator;
        return $this;
    }
    public function getServiceLocator(){
        return $this->serviceLocator;
    }
}