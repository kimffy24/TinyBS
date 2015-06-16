<?php
/**
 * Created by PhpStorm.
 * User: jiefzz
 * Date: 5/25/15
 * Time: 4:32 PM
 */

namespace TinyBS\Utils;

use Exception;

use TinyBS\BootStrap\BootStrap;

class ExtendHandler {
	
	static public function registerExceptionHandler(){
		set_exception_handler(array(__CLASS__, 'exceptionHandler'));
	}
	
	static public function registerErrorHandler(){
		set_error_handler(array(__CLASS__, 'errorHandler'));
	}

    static public function errorHandler($errorNo, $errorStr, $errorFile, $errorLine) {
        self::defaultErrorHandler($errorNo, $errorStr, $errorFile, $errorLine);
    }

    static public function exceptionHandler(Exception $exception) {
        self::prepareConfigure();
        if (self::$target) {
            die(call_user_func_array(
                self::$target,
                array(self::getLastCoreObject()->getServiceManager(), $exception)
            ));
        }
        self::defaltExceptionHandler($exception);
    }

    static private $lastCore = null;
    static private $target = null;

    /**
     * @return BootStrap
     */
    static private function getLastCoreObject(){
    	if(!self::$lastCore)
    		self::$lastCore = BootStrap::getLastRequestBootstrapObject();
    	return self::$lastCore;
    }
	
    static private function prepareConfigure() {
        $lastCore = self::getLastCoreObject();
        
        if (!$lastCore->getServiceManager()->has('TinyBS\RouteMatch\Route'))
            return;

        $routeMatch = $lastCore->getServiceManager()->get('TinyBS\RouteMatch\Route');
        $matchNamespace = $routeMatch->getMatchNamespace();
        if ($matchNamespace) {
            $config = $lastCore->getServiceManager()->get('config');

            if(isset($config['exception_switch'][$matchNamespace]) && $config['exception_switch'][$matchNamespace]==flase)
                exit(0);
			
            if(!self::$target)
            	if (isset($config['exception_handler'][$matchNamespace]) && is_callable($config['exception_handler'][$matchNamespace]))
                	self::$target = $config['exception_handler'][$matchNamespace];
        }

    }
    
    static private function defaltExceptionHandler(Exception $exception) {
        $msg = "Uncaught exception: ".$exception->getMessage()."<br />";
        $msg .= $exception->getTraceAsString();
        self::defaultShow($msg,true);
    }

    static private function defaultErrorHandler($errorNo, $errorStr, $errorFile, $errorLine) {
        switch ($errorNo) {
            case E_ERROR:
                $msg = "ERROR: [ID $errorNo] $errorStr (Line: $errorLine of $errorFile)<br />".
                "程序已经停止运行，请联系管理员。";
                self::defaultShow($msg,true);
                //遇到Error级错误时退出脚本
                break;
            case E_WARNING:
                $msg = "WARNING: [ID $errorNo] $errorStr (Line: $errorLine of $errorFile)";
                self::defaultShow($msg);
                break;
            default:
                //不显示Notice级的错误
                break;
        }

        return;
    }
    
    static private function defaultShow($msg, $fatal=false){
    	echo $msg;
    	if($fatal)
    		die(-1);
    }
} 