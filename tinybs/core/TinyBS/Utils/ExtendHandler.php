<?php
/**
 * Created by PhpStorm.
 * User: jiefzz
 * Date: 5/25/15
 * Time: 4:32 PM
 */

namespace TinyBS\Utils;

use TinyBS\BootStrap\BootStrap;

class ExtendHandler {
	
	public function __construct(BootStrap $core){
		$this->lastCore = $core;
	}
	
	public function registerExceptionHandler(){
		set_exception_handler(array(&$this, 'exceptionHandler'));
	}
	
	public function registerErrorHandler(){
		set_exception_handler(array(&$this, 'errorHandler'));
	}

    public function errorHandler($errorNo, $errorStr, $errorFile, $errorLine) {
        $this->defaultErrorHandler($errorNo, $errorStr, $errorFile, $errorLine);
    }

    public function exceptionHandler($exception) {
        $this->prepareConfigure();
        if ($this->target) {
            die(call_user_func_array(
                $this->target,
                array($this->getLastCoreObject()->getServiceManager(), $exception)
            ));
        }
        $this->defaltExceptionHandler($exception);
    }

    private $lastCore = null;
    private $target = null;

    /**
     * @return BootStrap
     */
    private function getLastCoreObject(){
    	return $this->lastCore;
    }
	
    private function prepareConfigure() {
        $lastCore = $this->lastCore;
        
        if (!$lastCore->getServiceManager()->has('TinyBS\RouteMatch\Route'))
            return;

        $routeMatch = $lastCore->getServiceManager()->get('TinyBS\RouteMatch\Route');
        $matchNamespace = $routeMatch->getMatchNamespace();
        if ($matchNamespace) {
            $config = $lastCore->getServiceManager()->get('config');

            if(isset($config['exception_switch'][$matchNamespace]) && $config['exception_switch'][$matchNamespace]==flase)
                exit(0);
			
            if(!$this->target)
            	if (isset($config['exception_handler'][$matchNamespace]) && is_callable($config['exception_handler'][$matchNamespace]))
                	$this->target = $config['exception_handler'][$matchNamespace];
        }

    }
    
    private function defaltExceptionHandler($exception) {
        $msg = "Uncaught exception: ".$exception->getMessage()."<br />";
        $this->defaultShow($msg,true);
    }

    private function defaultErrorHandler($errorNo, $errorStr, $errorFile, $errorLine) {
        switch ($errorNo) {
            case E_ERROR:
                $msg = "ERROR: [ID $errorNo] $errorStr (Line: $errorLine of $errorFile)<br />".
                "程序已经停止运行，请联系管理员。";
                $this->defaultShow($msg,true);
                //遇到Error级错误时退出脚本
                break;
            case E_WARNING:
                $msg = "WARNING: [ID $errorNo] $errorStr (Line: $errorLine of $errorFile)";
                $this->defaultShow($msg);
                break;
            default:
                //不显示Notice级的错误
                break;
        }

        return;
    }
    
    private function defaultShow($msg, $fatal=false){
    	echo $msg;
    	if($fatal)
    		die(-1);
    }
} 