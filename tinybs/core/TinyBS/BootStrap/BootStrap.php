<?php

namespace TinyBS\BootStrap;

use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\ArrayUtils;
use TinyBS\RouteMatch\Route;

define('USER_CONFIG_DIR', TINYBSROOT.DS.'config');
define('TINY_CONFIG_DIR', TINYBSROOT.DS.'tinybs'.DS.'config');
define('MODULECONFIG', TINYBSROOT.DS.'src'.DS.'main'.DS.'config');
define('MODULELOCATION', TINYBSROOT.DS.'src'.DS.'main'.DS.'src');

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
	
	const PSR_0_CONFIG_NAME = 'config.psr0.php';
	const PSR_4_CONFIG_NAME = 'config.psr4.php';
	const CLASSMAP_CONFIG_NAME = 'config.classmap.php';
	const LIB_MODULE_CONFIG_NAME = 'config.lib.module.php';
	const MODULE_CONFIG_NAME = 'config.module.php';
	
	private static $preLoadConfigFiles = array (
			self::PSR_0_CONFIG_NAME,
			self::PSR_4_CONFIG_NAME,
			self::CLASSMAP_CONFIG_NAME 
	);
	private static $postLoadConfigFiles = self::MODULE_CONFIG_NAME;
	
	static public function initialize() {
		//load  setting for ComposerAutoloader.
		BootStrap::prepareComposerAutoload ();
		//build an instance of \TinyBS\BootStrap\BootStrap.
		$core = new BootStrap ( new ServiceManager () );
		//load setting for inner ServiceManager that inside above instance.
		BootStrap::initServiceManager($core->getServiceManager());
		return $core;
	}
    static public function loadUserConfig(self $core){
		//load user module
		BootStrap::prepareUserLibModule();
		
		$moduleConfigName = USER_CONFIG_DIR.DS.BootStrap::$postLoadConfigFiles;
		if ( ( $fileName = stream_resolve_include_path( $moduleConfigName ) ) === false )
		    throw new \RuntimeException('At '.$moduleConfigName.' : There no config file about modules!');
		$modules = require $fileName;
		$moduleConfigs = static::loadModulesConfig($modules);
		
		$libModuleConfigName = USER_CONFIG_DIR.DS.BootStrap::LIB_MODULE_CONFIG_NAME;
		if ( ( $libFileName = stream_resolve_include_path( $libModuleConfigName ) ) === false )
		    throw new \RuntimeException('At '.$libModuleConfigName.' : There no config file about modules!');
		$libModules = require $libFileName;
		$libModuleConfigs = static::loadModulesConfig($libModules, false);
		
		$allConfigs = ArrayUtils::merge($libModuleConfigs, $moduleConfigs);
		$core->getServiceManager()->setService('config', $allConfigs);
		static::configServiceManager($core->getServiceManager());
    }
	static public function render(self $core, $bootstrapResult){
	    var_dump($bootstrapResult);
	    return $bootstrapResult;
	}
	/**
	 * Framework running.
	 * 
	 * @return \TinyBS\BootStrap\BootStrap
	 */
	static public function run(){
		$core = static::initialize();
		static::loadUserConfig($core);
		Route::loadModuleRoute($core);
		return static::render($core, Route::dispatch($core));
	}
	
	/**
	 * load {,tinybs/}config/config.{psr0,psr4,classmap}.php into ComposerAutoloader
	 * @author JiefzzLon
	 * @return null
	 */
	static public function prepareComposerAutoload(){
	    foreach ( BootStrap::$preLoadConfigFiles as $v )
	        BootStrap::setConfigIntoComposerAutoloader ( $v, true );
	    foreach ( BootStrap::$preLoadConfigFiles as $v )
	        BootStrap::setConfigIntoComposerAutoloader ( $v );
	}

	/**
	 * load config/config.lib.module.php into ComposerAutoloader
	 * @author JiefzzLon
	 * @return null
	 */
	static public function prepareUserLibModule() {
	    $ModuleLibConfigName = USER_CONFIG_DIR.DS.self::LIB_MODULE_CONFIG_NAME;
	    if ( ( $fileName = stream_resolve_include_path( $ModuleLibConfigName ) ) !== false ){
	        $libModules = require $fileName;
	        $composerAutoloader = ComposerAutoloader::getComposerAutoloader ();
	        //static::loadModulesConfig($libModules, false);
	        foreach ($libModules as $v) {
	            if(is_string($v)){
	                $composerAutoloader->set ( $v, MODULELOCATION );
	            } elseif(is_array($libModules) and count($libModules)>1) {
	                // >>>specified module name
	                $moduleName = isset($libModules['module_name'])?$libModules['module_name']:$libModules[0];
	                // >>>specified the module path
	                $modulePath = isset($libModules['module_path'])?$libModules['module_path']:$libModules[1];
	                $composerAutoloader->set ( $moduleName, $modulePath.DS.'src' );
	            }
	        }
	        //return $libModules;
	    }
	    //return array();
	}
	
	/**
	 * load user module's config file and return a combine set.
	 * @param array $modules
	 * @throws \RuntimeException
	 * @return array <multitype:, array, unknown>
	 */
	static private function loadModulesConfig($modules, $strict = true){
		$moduleConfig = array();
		foreach($modules as $userModule){
		    if(is_string($userModule)){
    			$tempFileName = MODULECONFIG.DS.$userModule.DS.'module.config.php';
    			if( ( $moduleConfigFile = stream_resolve_include_path( $tempFileName ) ) === false )
    			    if($strict)
    				    throw new \RuntimeException("There no config file '.$tempFileName.' on loading module ".$userModule.'. ');
    			    else continue;
    			$moduleDetails = require $moduleConfigFile;
    			$moduleConfig = ArrayUtils::merge($moduleConfig, $moduleDetails);
		    } elseif(is_array($userModule) and count($userModule)>1) {
		        // >>>specified module name
		        $moduleName = isset($userModule['module_name'])?$userModule['module_name']:$userModule[0];
		        // >>>specified the module path
		        $modulePath = isset($userModule['module_path'])?$userModule['module_path']:$userModule[1];
		        // >>> check if the module config file is exits.
    			$tempFileName = $modulePath.DS.'config'.DS.'module.config.php';
		        if(!file_exists($tempFileName))
    			    if($strict)
    				    throw new \RuntimeException("There no config file '.$tempFileName.' on loading module ".$moduleName.'. ');
    			    else continue;
		        // >>> load the module config file.
    			$moduleDetails = require $tempFileName;
    			$moduleConfig = ArrayUtils::merge($moduleConfig, $moduleDetails);
		    }
		}
		return $moduleConfig;
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
			TINY_CONFIG_DIR . DS . $configName:
			USER_CONFIG_DIR . DS . $configName;
		if (file_exists ( $config ))
			$keys = require $config;
		else
			return;
		$composerAutoloader = ComposerAutoloader::getComposerAutoloader ();
		
		if (is_array ( $keys ) and count ( $keys ))
			switch ($configName) {
				case self::PSR_0_CONFIG_NAME :
					foreach ( $keys as $k => $v )
						$composerAutoloader->set ( $k, BootStrap::generalPath ( $v ) );
					break;
				case self::PSR_4_CONFIG_NAME :
					foreach ( $keys as $k => $v )
						$composerAutoloader->setPsr4 ( $k, BootStrap::generalPath ( $v ) );
					break;
				case self::CLASSMAP_CONFIG_NAME :
					$composerAutoloader->addClassMap ( $keys );
					break;
			}
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

	static private function configServiceManager(ServiceManager $serviceManager, $configArray = array()){
	    if(count($configArray)<1){
	        $allConfigArray = $serviceManager->get ( 'Config' );
	        $configArray = $allConfigArray['service_manager'];
	    }
	    foreach ($configArray as $k => $v) {
	        $method = '';
	        $args =  array();
	        switch($k){
	            case 'abstract_factories':
	                $method = 'addAbstractFactory';
	                break;
	            case 'aliases':
	                $method = 'setAlias';break;
	            case 'factories':
	                $method = 'setFactory';break;
	            case 'invokables':
	                $method = 'setInvokableClass';break;
	            case 'services':
	                $method = 'setService';break;
	            case 'shared':
	                $method = 'setShared';break;
	            default:
	                throw new \RuntimeException(__METHOD__.'() There no service '.$k.' in ServiceManager');
	        }
	        foreach ($v as $key => $value) {
                $args = ($method != 'abstract_factories')?array($key, $value):array($value);
                call_user_func_array(array($serviceManager, $method),$args);
	        }
	    }
	}
}