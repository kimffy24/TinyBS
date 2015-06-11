<?php
namespace TinyBS\BootStrap;

use RuntimeException;
use Zend\ServiceManager\ServiceManager;


class ServiceManagerUtils
{
	/**
	 * load config in TINYBSROOT/tinybs/config/config.servicemanager.{factory,alias,invokableclass}.php
	 *
	 * @param ServiceManager $serviceManager
	 * @author JiefzzLon
	 */
	static public function initServiceManager(ServiceManager $serviceManager) {
	    $serviceManager->setService ( 'ServiceManager', $serviceManager );
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
	                $serviceManager->$v($key, $value);
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
	                throw new RuntimeException(__METHOD__.'() There no service '.$k.' in ServiceManager');
	        }
	        foreach ($v as $key => $value) {
                $args = ($method != 'abstract_factories')?array($key, $value):array($value);
                call_user_func_array(array($serviceManager, $method),$args);
	        }
	    }
	}
}