<?php
namespace TinyBS\BootStrap;

use TinyBS\Utils\RuntimeException;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class ServiceManagerUtils {
	/**
	 * load config in TINYBSROOT/tinybs/config/config.servicemanager.{factory,alias,invokableclass}.php
	 *
	 * @param ServiceManager $serviceManager
	 * @author JiefzzLon
	 */
	static public function initServiceManager(ServiceManager $serviceManager) {
	    $initServiceManager = array(
	        'factory' => 'setFactory',
	        'alias' => 'setAlias',
	        'invokableclass' => 'setInvokableClass',
	    );
	    foreach( $initServiceManager as $k => $v ) {
	        $tmpPath = TINYBSROOT . DS . 'tinybs' . DS . 'config' . DS . 'config.servicemanager.'.$k.'.php';
	        if (($result = stream_resolve_include_path($tmpPath)) !== false) {
	            $config = require $result;
	            if(!count($config)) continue;
	            foreach($config as $key=> $value)
	                call_user_func_array(array($serviceManager, $v), array($key, $value));
	        }
	    }
	}

	/**
	 * configure the servicemanager with the module config set
	 * @param ServiceManager $serviceManager
	 * @param array $configArray
	 * @throws RuntimeException
	 */
	static public function configServiceManager(ServiceManager $serviceManager, $configArray = array()){
	    if(count($configArray)<1){
	        $allConfigArray = $serviceManager->get ( 'Config' );
	        $configArray = $allConfigArray['service_manager'];
	    }
	    foreach ($configArray as $k => $v) {
	        $method = '';
	        $args =  array();
	        switch($k){
	            case 'abstract_factories':
	                $method = 'addAbstractFactory';break;
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
	                throw new RuntimeException(__METHOD__.'() There no service '.$k.' in ServiceManager');
	        }
	        foreach ($v as $key => $value) {
                $args = ($method != 'abstract_factories')?array($key, $value):array($value);
                call_user_func_array(array($serviceManager, $method),$args);
	        }
	    }
	}
	
	static public function registBaseInitializer(ServiceManager $serviceManager){
		$initializers = array(
				'ServiceLocatorAwareInterface' => function ($instance, ServiceLocatorInterface $serviceLocator) {
					if ($serviceLocator instanceof ServiceManager && $instance instanceof ServiceManagerAwareInterface) {
						$instance->setServiceManager($serviceLocator);
					}
				},
				'ServiceManagerAwareInterface' => function ($instance, ServiceLocatorInterface $serviceLocator) {
					if ($instance instanceof ServiceLocatorAwareInterface) {
						$instance->setServiceLocator($serviceLocator);
					}
				},
		);
		foreach($initializers as $initializer)
			$serviceManager->addInitializer($initializer);
	}
}