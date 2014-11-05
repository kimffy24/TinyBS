<?php

namespace TinyBS\BootStrap;

use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\ArrayUtils;
use TinyBS\RouteMatch\Route;

class BootStrap {
	private $serviceManager;
	public function __construct(ServiceManager $sm) {
		$this->serviceManager = $sm;
	}
	/**
	 * return default ServiceManager instance
	 * @return \Zend\ServiceManager\ServiceManager
	 * @author JiefzzLon
	 */
	public function getServiceManager() {
		return $this->serviceManager;
	}
	
	
	private static $preLoadConfigFiles = array (
			'config.psr0.php',
			'config.psr4.php',
			'config.classmap.php' 
	);
	private static $postLoadConfigFiles = 'config.module.php';
	static public function prepareUserComposerAutoload(){
		foreach ( BootStrap::$preLoadConfigFiles as $v )
			BootStrap::setConfigIntoComposerAutoloader ( $v );
	}
	static public function prepareUserModule() {
		BootStrap::setConfigIntoComposerAutoloader ( BootStrap::$postLoadConfigFiles );
	}
	
	/**
	 * Framework running.
	 * 
	 * @return \TinyBS\BootStrap\BootStrap
	 */
	static public function initialize() {
		//build an instance of \TinyBS\BootStrap\BootStrap.
		$core = new BootStrap ( new ServiceManager () );
		//load setting for inner ServiceManager that inside above instance.
		BootStrap::initServiceManager($core->getServiceManager());
		//load user setting for ComposerAutoloader.
		BootStrap::prepareUserComposerAutoload ();
		//load user module
		BootStrap::prepareUserModule();
		
		$moduleConfig = array();
		$modules = require USER_CONFIG_DIR.DS.BootStrap::$postLoadConfigFiles;
		foreach($modules as $userModule){
			$configModule = MODULECONFIG.DS.$userModule.DS.'config.php';
			if(!file_exists($configModule))
				throw new \RuntimeException("There no config file '.$configModule.' on loading module ".$userModule.'. ');
			$moduleDetails = require $configModule;
			$moduleConfig = ArrayUtils::merge($moduleConfig, $moduleDetails);
		}
		$core->getServiceManager()->setService('config', $moduleConfig);
		return $core;
	}
	
	/**
	 * load config in TINYBSROOT/tinybs/config/config.servicemanager.{factory,alias,invokableclass}.php
	 * 
	 * @param ServiceManager $serviceManager
	 * @author JiefzzLon
	 */
	static private function initServiceManager(ServiceManager $serviceManager) {
		$serviceManager->setService ( 'ServiceManager', $serviceManager );
		$serviceManager->setAlias ( 'Zend\ServiceManager\ServiceLocatorInterface', 'ServiceManager' );
		$serviceManager->setAlias ( 'Zend\ServiceManager\ServiceManager', 'ServiceManager' );
		$initServiceManager = [
			'factory' => 'setFactory',
			'alias' => 'setAlias',
			'invokableclass' => 'setInvokableClass',
		];
		foreach( $initServiceManager as $k => $v ) {
			$result = stream_resolve_include_path(
					TINYBSROOT . DS . 'tinybs' . DS . 'config' . DS . 'config.servicemanager.'.$k.'.php'
			);
            if ($result !== false) {
            	$config = require $result;
            	if(!count($config)) continue;
            	foreach($config as $key=> $value)
                	$serviceManager->$v($key, $value);
            }
		}
	}
	
	/**
	 * general the path to Absolutely Path
	 *
	 * @param unknown $path        	
	 * @return unknown
	 */
	static private function generalPath($path) {
		if (is_string ( $path )) {
			if ($path [0] == '/')
				return $path;
			else if ($path [0] == '.') {
				if ($path [1] == '.')
					return BootStrap::generalPath ( TINYBSROOT . DS . substr ( $path, 2 ) );
				else if ($path [1] == DS)
					return BootStrap::generalPath ( TINYBSROOT . DS . 'config' . substr ( $path, 1 ) );
				else
					return BootStrap::generalPath ( TINYBSROOT . DS . 'config' . DS . $path );
			} else if (preg_match ( '/^[a-zA-Z0-9]\\:/', $path ))
				return $path;
		}
		return $path;
	}
	
	/**
	 * set config into ComposerAutoloader
	 * @param string $configName
	 * @param boolean $isSysConfig
	 * @author JiefzzLon
	 */
	static private function setConfigIntoComposerAutoloader($configName, $isSysConfig = false) {
		$config = ($isSysConfig)?
			TINYBSROOT . DS . 'tinybs' . DS . 'config' . DS . $configName:
			TINYBSROOT . DS . 'config' . DS . $configName;
		if (file_exists ( $config ))
			$keys = require $config;
		else
			return;
		$composerAutoloader = ComposerAutoloader::getComposerAutoloader ();
		
		if (is_array ( $keys ) and count ( $keys ))
			switch ($configName) {
				case BootStrap::$preLoadConfigFiles [0] :
					foreach ( $keys as $k => $v )
						$composerAutoloader->set ( $k, BootStrap::generalPath ( $v ) );
					break;
				case BootStrap::$preLoadConfigFiles [1] :
					foreach ( $keys as $k => $v )
						$composerAutoloader->setPsr4 ( $k, BootStrap::generalPath ( $v ) );
					break;
				case BootStrap::$preLoadConfigFiles [2] :
					$composerAutoloader->addClassMap ( $keys );
					break;
			}
	}
}