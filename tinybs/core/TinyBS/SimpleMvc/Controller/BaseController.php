<?php
namespace TinyBS\SimpleMVC\Controller;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class BaseController implements ServiceLocatorAwareInterface
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