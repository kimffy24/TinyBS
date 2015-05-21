<?php

namespace TinyBS\SimpleMvc\Utils;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class AbstractModuleInitialization
	implements ModuleInitializationInterface, ServiceLocatorAwareInterface {
	public function __construct(ServiceManager $sm){
		$this->serviceManager = $sm;
	}

	public function setServiceLocator(ServiceLocatorInterface $sl){
		if(!$this->serviceManager)
			$this->serviceManager = $sl;
		return $this;
	}
	public function getServiceLocator(){
		return $this->getServiceManager();
	}
	
	protected function getServiceManager(){
		if(!$this->serviceManager)
			throw new ModuleInitializationException();
		return $this->serviceManager;
	}
	

	private $serviceManager;
}