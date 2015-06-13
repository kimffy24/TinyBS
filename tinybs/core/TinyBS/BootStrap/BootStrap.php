<?php

namespace TinyBS\BootStrap;

use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\ArrayUtils;

use TinyBS\RouteMatch\Route;
use TinyBS\Utils\RuntimeException;
use TinyBS\Utils\RuntimeLogger;
use TinyBS\Utils\NullLogger;
use TinyBS\Utils\EnvironmentTools;
use TinyBS\Utils\ExtendHandler;

define('USER_CONFIG_DIR', TINYBSROOT.DS.'config');
define('TINY_CONFIG_DIR', TINYBSROOT.DS.'tinybs'.DS.'config');
define('MODULECONFIG', TINYBSROOT.DS.'src'.DS.'main'.DS.'config');
define('MODULELOCATION', TINYBSROOT.DS.'src'.DS.'main'.DS.'src');

class BootStrap {

    const LIB_MODULE_CONFIG_NAME = 'config.lib.module.php';
    const MODULE_CONFIG_NAME = 'config.module.php';


    /**
     * TinyBS Framework Entrance.
     *
     * @return \TinyBS\BootStrap\BootStrap
     */
    static public function run(){
    	$logger = EnvironmentTools::getRuntimeLogger()->info(__METHOD__.' was invoked!');
    	ExtendHandler::registerExceptionHandler();
    	ExtendHandler::registerErrorHandler();
    	EnvironmentTools::registerShutdown(function() use (&$logger) {
    		$logger->info('TinyBS\Utils\EnvironmentTools::registerShutdown all is finished!');
    	});
    	EnvironmentTools::topEnvironmentPrepare();
    	$logger->info(__METHOD__.' invoked EnvironmentTools::topEnvironmentPrepare()!');

    	$logger->info(__METHOD__.' initialize a core object!');
    	$core = self::initialize();
    	
    	$logger->info(__METHOD__.' start route match and do dispatch!');
    	$route = new Route($core);
    	$route->loadModuleRoute()->dispatch();
    }

    /**
     * @return \TinyBS\BootStrap\BootStrap
     */
    static public function getLastRequestBootstrapObject(){
        return self::$requestBootstrapObject;
    }
    

    static private $requestBootstrapObject = null;
    static private function initialize() {
    	$serviceManager = null;
    	if(($core=QuickBootStrapUtils::restore())==null){
    		$serviceManager = new ServiceManager ();
    		ServiceManagerUtils::initServiceManager($serviceManager);
    		$core = new static($serviceManager);
    		$core->loadUserConfig();
    		QuickBootStrapUtils::persistent($core);
    	} else {
    		self::$requestBootstrapObject = $core;
    		$serviceManager = $core->getServiceManager();
    	}
    
    	ServiceManagerUtils::registBaseInitializer($serviceManager);
    	$core->loadModuleIntoComposerAutoloader(ComposerAutoloader::getComposerAutoloader());
    	return $core;
    }
    
	/**
	 * return default ServiceManager instance
	 * @return \Zend\ServiceManager\ServiceManager
	 * @author JiefzzLon
	 */
	public function getServiceManager() {
		return $this->serviceManager;
	}

	
    /**
     * we do not use public access here
     * @throws RuntimeException
     */
    protected function __construct(ServiceManager $sm){
    	if(self::$requestBootstrapObject)
    		throw new RuntimeException("Multi tinybs core object is not allowed!");
    	self::$requestBootstrapObject = $this;
    	$this->serviceManager = $sm;
    }


    
    private $serviceManager = null;
	private $modulePathMap = array();
	/**
	 * add the match module path into composer autoloader
	 * @param $moduleName
	 */
	private function loadModuleIntoComposerAutoloader($composerAutoloader){
		foreach($this->modulePathMap as $namespace => $path)
			$composerAutoloader->set($namespace, $path);
	}
	
	/**
	 * load the config of user module which specified in config/config.{lib.,}module.php
	 * @throws RuntimeException
	 */
    private function loadUserConfig(){
		$moduleConfigs = array();
		$loadConfigStep = array(
		    array(USER_CONFIG_DIR.DS.self::MODULE_CONFIG_NAME, true),
		    array(USER_CONFIG_DIR.DS.self::LIB_MODULE_CONFIG_NAME, false)
		);
		foreach ($loadConfigStep as $v) {
		    $moduleConfigName = $v[0];
    		if ( ( $libFileName = stream_resolve_include_path( $moduleConfigName ) ) === false )
    		    throw new RuntimeException('file '.$moduleConfigName.' : not exist!');
    		$modules = require $libFileName;
    		$moduleConfigs = ArrayUtils::merge($this->loadModulesConfig($modules, $v[1]), $moduleConfigs);;
		}
		
		$this->getServiceManager()->setService('config', $moduleConfigs);
		ServiceManagerUtils::configServiceManager($this->getServiceManager());
    }
	
	/**
	 * load user module's config file and return a merge set.
	 * and push there path into $this->modulePathMap;
	 * @param array $modules
	 * @param boolean $strict whether throw a RuntimeException or not when the module config doesn't exist.
	 * @throws RuntimeException
	 * @return array <multitype:, array, unknown>
	 */
	private function loadModulesConfig($modules, $strict = true){
		$moduleConfig = array();
		foreach($modules as $userModule){
			$moduleConfigFile = '';
		    if(is_string($userModule)){
    			$tempFileName = MODULECONFIG.DS.$userModule.DS.'module.config.php';
    			if( ( $moduleConfigFile = stream_resolve_include_path( $tempFileName ) ) === false )
    			    if($strict)
    				    throw new RuntimeException("There no config file '.$tempFileName.' on loading module ".$userModule.'. ');
    			    else continue;
    			$this->modulePathMap[$userModule] = MODULELOCATION;
		    } elseif(is_array($userModule) and count($userModule)>1) {
		        $moduleName = isset($userModule['module_name'])?$userModule['module_name']:$userModule[0];
		        $modulePath = isset($userModule['module_path'])?$userModule['module_path']:$userModule[1];
				$tempFileName = $modulePath . DS . 'config' . DS . 'module.config.php';
				if (($moduleConfigFile = stream_resolve_include_path( $tempFileName ) ) === false )
    			    if($strict)
    				    throw new RuntimeException("There no config file '.$moduleConfigFile.' on loading module ".$moduleName.' configs.');
    			    else continue;
    			$this->modulePathMap[$moduleName] = $modulePath.DS.'src';
		    }
    		$moduleDetails = require $moduleConfigFile;
    		$moduleConfig = ArrayUtils::merge($moduleConfig, $moduleDetails);
		}
		return $moduleConfig;
	}
}