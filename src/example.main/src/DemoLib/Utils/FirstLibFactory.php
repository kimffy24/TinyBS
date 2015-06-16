<?php

namespace DemoLib\Utils;

use DemoLib\FirstLib;

use Zend\ServiceManager\FactoryInterface;

class FirstLibFactory implements FactoryInterface{
	/* (non-PHPdoc)
	 * @see \Zend\ServiceManager\FactoryInterface::createService()
	 */
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
		// TODO Auto-generated method stub
		return new FirstLib();
	}

}