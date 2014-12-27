<?php

namespace TinyBS\BootStrap;

use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\ArrayUtils;
use TinyBS\RouteMatch\Route;
use TinyBS\SimpleMvc\View\TinyBsRender;

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
	
	static private $preLoadConfigFiles = array (
			self::PSR_0_CONFIG_NAME,
			self::PSR_4_CONFIG_NAME,
			self::CLASSMAP_CONFIG_NAME 
	);
	static private $postLoadConfigFiles = self::MODULE_CONFIG_NAME;
	static private $modulePathMap = array();
	
	static public function initialize() {
		//load  setting for ComposerAutoloader.
		BootStrap::prepareComposerAutoload ();
		//build an instance of \TinyBS\BootStrap\BootStrap.
		$core = new BootStrap ( new ServiceManager () );
		//load setting for inner ServiceManager that inside above instance.
		ServiceManagerUtils::initServiceManager($core->getServiceManager());
		return $core;
	}
    static public function loadUserConfig(self $core){
		//load user module
		BootStrap::prepareUserLibModule();
		
		/* $moduleConfigName = ;
		if ( ( $fileName = stream_resolve_include_path( $moduleConfigName ) ) === false )
		    throw new \RuntimeException('At '.$moduleConfigName.' : There no config file about modules!');
		$modules = require $fileName;
		$moduleConfigs = static::loadModulesConfig($modules);
		
		$libModuleConfigName = ;
		if ( ( $libFileName = stream_resolve_include_path( $libModuleConfigName ) ) === false )
		    throw new \RuntimeException('At '.$libModuleConfigName.' : There no config file about modules!');
		$libModules = require $libFileName;
		$libModuleConfigs = static::loadModulesConfig($libModules, false); */
		
		$moduleConfigs = array();
		$loadConfigStep = array(
		    array(USER_CONFIG_DIR.DS.BootStrap::$postLoadConfigFiles, true),
		    array(USER_CONFIG_DIR.DS.BootStrap::LIB_MODULE_CONFIG_NAME, false)
		);
		foreach ($loadConfigStep as $v) {
		    $moduleConfigName = $v[0];
    		if ( ( $libFileName = stream_resolve_include_path( $moduleConfigName ) ) === false )
    		    throw new \RuntimeException('At '.$moduleConfigName.' : There no config file about modules!');
    		$modules = require $libFileName;
    		$moduleConfigs = ArrayUtils::merge(static::loadModulesConfig($modules, $v[1]), $moduleConfigs);;
		}
		
		//$allConfigs = ArrayUtils::merge($libModuleConfigs, $moduleConfigs);
		//$core->getServiceManager()->setService('config', $allConfigs);
		$core->getServiceManager()->setService('config', $moduleConfigs);
		ServiceManagerUtils::configServiceManager($core->getServiceManager());
    }

	/**
	 * Framework running.
	 * 
	 * @return \TinyBS\BootStrap\BootStrap
	 */
	static public function run(){
		$core = static::initialize();
		static::loadUserConfig($core);
		$route = new Route();
		$route->loadModuleRoute($core);
		$bootstrapResult = $route->dispatch($core);
		return TinyBsRender::render($core, $bootstrapResult);
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
	            } elseif(is_array($v) and count($v)>0) {
	                // >>>specified module name
	                $moduleName = isset($v['module_name'])?$v['module_name']:$v[0];
	                // >>>specified the module path
	                $modulePath = isset($v['module_path'])?$v['module_path']:$v[1];
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
	 * @param boolean $strict whether throw a RuntimeException or not when the module config doesn't exist.
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
    			// set the path map
    			static::$modulePathMap[$userModule] = MODULELOCATION;
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
    			static::$modulePathMap[$moduleName] = $modulePath.DS.'src';
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
    
	static public function loadSpecialModule($moduleName){
		$composerAutoloader = ComposerAutoloader::getComposerAutoloader();
		if(!$composerAutoloader)
		    throw new \RuntimeException('At '.__METHOD__.' : Composer\Autoload not load!');
	    if(isset(static::$modulePathMap[$moduleName]))
	        $composerAutoloader->set($moduleName, static::$modulePathMap[$moduleName]);
	}
}