<?php

namespace TinyBS\SimpleMvc\Utils;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

/**
 * the abstract module initialization class
 * implements ServiceManagerAwareInterface interface make it can set ServiceManager in it
 * @author Jiefzz
 *
 */
abstract class AbstractModuleInitialization
	implements ModuleInitializationInterface, ServiceManagerAwareInterface
{
	public function initModule(){}
	
	/**
	 * if the ServiceManager Object was not given while construct
	 * use this method to set ServiceManager in.
	 * @param \Zend\ServiceManager\ServiceManager $sm
	 * @return \TinyBS\SimpleMvc\Utils\AbstractModuleInitialization
	 */
	public function setServiceManager(ServiceManager $sm){
		if(!$this->serviceManager)
			$this->serviceManager = $sm;
		return $this;
	}
	
	/**
	 * get ServiceManager Object
	 * @return \Zend\ServiceManager\ServiceManager
	 */
	public function getServiceManager(){
		return $this->serviceManager;
	}

	/**
	 * (non-PHPdoc)
	 * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::getServiceLocator()
	 */
	public function getServiceLocator(){
		return $this->getServiceManager();
	}

	private $serviceManager;
}